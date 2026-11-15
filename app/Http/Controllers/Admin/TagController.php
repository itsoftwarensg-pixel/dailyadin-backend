<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // 1.67 ms
        /*$tags = Tag::withCount('contents')  // adds contents_count column
            ->search($request->search) // applies search filter
            ->latest('id')
            ->paginate(100)
            ->withQueryString();*/
        //        $tags = Tag::withCount('contents')->latest('id')->paginate(80);

        // Fast: 503 ms. without search filter
        /*$tags = DB::table('tags')
            ->leftJoin('bn_content_tag', 'tags.id', '=', 'bn_content_tag.tag_id')
            ->select('tags.*', DB::raw('COUNT(bn_content_tag.bn_content_id) as contents_count'))
            ->groupBy('tags.id', 'tags.name', 'tags.alias')
            ->orderByDesc('tags.id')
            ->paginate(100);*/

        // More faster query. 500 ms for any kind of paginate items
        /*$search = $request->search;
        $tags = DB::table('tags')
            ->leftJoin('bn_content_tag', 'tags.id', '=', 'bn_content_tag.tag_id')
            ->select('tags.*', DB::raw('COUNT(bn_content_tag.bn_content_id) as contents_count'))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tags.name', 'like', "%{$search}%")
                        ->orWhere('tags.alias', 'like', "%{$search}%");
                });
            })
            ->groupBy('tags.id', 'tags.name', 'tags.alias')
            ->orderByDesc('tags.id')
            ->paginate(500)
            ->withQueryString(); // preserve search query in pagination links*/


        // Used db index, 400ms
        /*$search = $request->search;
        $tags = DB::table('tags as t')
            ->leftJoin('bn_content_tag as bct', 't.id', '=', 'bct.tag_id')
            ->select(
                't.id',
                't.name',
                't.alias',
                't.status',
                DB::raw('COUNT(bct.bn_content_id) as contents_count')
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('t.name', 'like', "%{$search}%")
                        ->orWhere('t.alias', 'like', "%{$search}%");
                });
            })
            ->groupBy('t.id', 't.name', 't.alias')
            ->orderByDesc('t.id')
            ->paginate(100)
            ->withQueryString(); // preserve search in pagination links*/

        // New column in tags table (contents_count). 11 ms
        $search = $request->search;
        $tags = DB::table('tags')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('alias', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(100);

        return view('admin.tag.index', compact('tags'));
    }

    /*public function index(Request $request)
    {
        $search = $request->search;
        $page = $request->get('page', 1);
        $perPage = 100; // updated pagination

        // Get Redis prefix from config
        $prefix = config('cache.prefix'); // e.g., 'news_portal:'

        // Build cache key
        $redisKey = "{$prefix}tags:{$search}:page:{$page}:perpage:{$perPage}";

        // Attempt to get cached data
        $cached = Redis::get($redisKey);

        if ($cached) {
            $tags = unserialize($cached);
        } else {
            // Build query
            $tagsQuery = Tag::query();
            if ($search) {
                $tagsQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('alias', 'like', "%{$search}%");
            }

            $tags = $tagsQuery->latest('id')
                ->paginate($perPage)
                ->withQueryString();

            // Store in Redis forever
            Redis::set($redisKey, serialize($tags));
        }

        return view('admin.tag.index', compact('tags'));
    }*/


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'  => 'required|unique:tags,name',
                'image' => 'image',
            ]
        );

        $tag = new Tag();
        $tag->name              = $request->name;
        $tag->alias             = $request->alias;
        $tag->description       = $request->description;
        $tag->meta_title        = $request->meta_title;
        $tag->meta_keywords     = $request->meta_keywords;
        $tag->meta_description  = $request->meta_description;
        $tag->sorting           = $request->sorting;
        $tag->status            = $request->status;

        if ($request->image) {
            $tag->image = FileUploadHelper::uploadFile($request->image);
        }

        $tag->save();

        // Cache is automatically flushed via model events

        return redirect()->route('tag.index')->with('message', 'Successfully new tag information created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('admin.tag.edit', [
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate(
            [
                'name'  => 'required|unique:tags,name,' . $tag->id,
                'image' => 'image',
            ]
        );

        $tag->name              = $request->name;
        $tag->alias             = $request->alias;
        $tag->description       = $request->description;
        $tag->meta_title        = $request->meta_title;
        $tag->meta_keywords     = $request->meta_keywords;
        $tag->meta_description  = $request->meta_description;
        $tag->sorting           = $request->sorting;
        $tag->status            = $request->status;

        if ($request->image) {
            $tag->image = FileUploadHelper::uploadFile($request->image, $tag->image);
        }

        $tag->update();

        // Cache is automatically flushed via model events

        return redirect()->route('tag.index')->with('message', 'Successfully tag information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Mark as inactive
        $tag->status = StatusEnum::INACTIVE->value;
        $tag->save();

        // Delete
        $tag->delete();

        // Cache is automatically flushed via model events

        return redirect()->route('tag.index')->with('message', 'Tag has been deleted from this system!');
    }
}
