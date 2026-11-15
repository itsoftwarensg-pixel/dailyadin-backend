<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::latest('id')->paginate(15)->withQueryString();

        return view('admin.video.index', [
            'videos' => $videos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.video.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'embed_code'    => 'required',
                // 'photo'         => 'image',
            ]
        );

        $video = new Video();
        $video->video_category_id    = $request->video_category_id;
        $video->video_type           = $request->video_type;
        $video->title                = $request->title;
        $video->embed_code           = $request->embed_code;
        $video->description          = $request->description;

        /*if ($request->photo) {
            $video->photo = FileUploadHelper::uploadFile($request->photo, $video->photo);
        }*/

        $video->save();

        return redirect()->route('video.index')->with('message', 'New video added!');
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
    public function edit(Video $video)
    {
        return view('admin.video.edit', [
            'video' => $video,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $request->validate(
            [
                'embed_code'    => 'required',
                // 'photo'         => 'image',
            ]
        );

        $video->video_category_id    = $request->video_category_id;
        $video->video_type           = $request->video_type;
        $video->title                = $request->title;
        $video->embed_code           = $request->embed_code;
        $video->description          = $request->description;
        $video->status               = $request->status;

        /*if ($request->photo) {
            $video->photo = FileUploadHelper::uploadFile($request->photo, $video->photo);
        }*/

        $video->update();

        return redirect()->route('video.index')->with('message', 'Successfully video information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        if (auth()->user()->role_id == 1) {
            // Mark as inactive
            $video->status = StatusEnum::INACTIVE->value;
            $video->save();

            // Soft delete
            $video->delete();

            return redirect()->route('video.index')
                ->with('message', 'Video has been deleted from this system!');
        }

        return redirect()->route('video.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }
}
