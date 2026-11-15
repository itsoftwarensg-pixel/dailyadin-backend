<?php

namespace App\Http\Controllers\API;

use App\Enums\BnManagePositionEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\BnContentResource;
use App\Models\BnCategory;
use App\Models\BnContent;
use App\Models\BnManagePosition;
use App\Models\BnManageSpecialEvent;
use App\Models\Tag;
use App\Models\PhotoAlbum;
use App\Models\Video;
use App\Http\Resources\PhotoAlbumResource;
use App\Http\Resources\VideoResource;
use App\Models\HomeVideoSection;
use App\Models\StarProgram;
use App\Models\BnSubCategory;
use App\Http\Resources\HomeVideoSectionResource;
use App\Http\Resources\StarProgramResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BnHomeController extends Controller
{
    /**
     * Get dynamic homepage data based on includes
     */
    public function index(Request $request)
    {
        $includes = $request->include ? explode(',', $request->include) : [];
        $homeData = [];

        // Pre-fetch categories if any numeric IDs or aliases are provided
        $categoryIdsOrAliases = array_filter($includes, fn($item) => !in_array(trim($item), ['lead', 'special', 'special_event', 'scroll', 'scroll_pos', 'trending_tag', 'recent', 'popular', 'photo', 'video', 'home_video', 'star_program']));
        $categories = BnCategory::whereIn('id', $categoryIdsOrAliases)
            ->orWhereIn('alias', $categoryIdsOrAliases)
            ->get()
            ->keyBy(fn($cat) => $cat->id); // We'll also need it by alias

        $categoriesByAlias = $categories->keyBy('alias');

        foreach ($includes as $item) {
            $item = trim($item);
            if (empty($item)) continue;

            if ($item === 'lead') {
                $homeData['lead'] = $this->getLeadContents();
            } elseif ($item === 'scroll') {
                $homeData['scroll'] = $this->getScrollFlagContents();
            } elseif ($item === 'scroll_pos') {
                $homeData['scroll_pos'] = $this->getScrollPositionContents();
            } elseif ($item === 'trending_tag') {
                $homeData['trending_tag'] = $this->getTrendingTagContents();
            } elseif ($item === 'recent') {
                $homeData['recent'] = $this->getRecentContents();
            } elseif ($item === 'popular') {
                $homeData['popular'] = $this->getPopularContents();
            } elseif ($item === 'special_event') {
                $homeData['special_event'] = $this->getSpecialEvent();
            } elseif ($item === 'special') {
                $homeData['special'] = $this->getSpecialContents();
            } elseif ($item === 'photo') {
                $homeData['photo'] = $this->getPhotoContents();
            } elseif ($item === 'video') {
                $homeData['video'] = $this->getVideoContents();
            } elseif ($item === 'home_video') {
                $homeData['home_video'] = $this->getHomeVideoContents();
            } elseif ($item === 'opinion') {
                $category = $categories[$item] ?? $categoriesByAlias[$item] ?? null;
                if ($category) {
                    $homeData[$category->alias] = $this->getOpinionContents($category);
                }
            } elseif ($item === 'star_program') {
                $homeData['star_program'] = $this->getStarProgramContents();
            } else {
                // Check if it's a category by ID or alias
                $category = $categories[$item] ?? $categoriesByAlias[$item] ?? null;

                if ($category) {
                    $homeData[$category->alias] = $this->getCategoryHomeContents($category);
                } else {
                    // Fallback to SubCategory
                    $subCategory = BnSubCategory::with('category')->where('alias', $item)->orWhere('id', $item)->first();
                    if ($subCategory) {
                        $homeData[$subCategory->alias] = $this->getSubCategoryHomeContents($subCategory);
                    } else {
                        // Fallback to Tag
                        $tag = Tag::where('alias', $item)->orWhere('id', $item)->first();
                        if ($tag) {
                            $homeData[$tag->alias] = $this->getTagHomeContents($tag);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $homeData
        ]);
    }

    /**
     * Fetch lead contents
     */
    private function getLeadContents()
    {
        $contents = $this->fetchPositionContents('lead');
        return [
            'name' => 'Lead News',
            'url'  => null,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch Special contents
     */
    private function getSpecialContents()
    {
        $contents = $this->fetchPositionContents(BnManagePositionEnum::SPECIAL->value);
        return [
            'name' => 'Special News',
            'url'  => null,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch scroll contents by position
     */
    private function getScrollPositionContents()
    {
        $contents = $this->fetchPositionContents('scroll');
        return [
            'name' => 'Scroll News (Position)',
            'url'  => null,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch scroll contents by show_in_scroll flag (Latest 10)
     */
    private function getScrollFlagContents()
    {
        $contents = BnContent::with(['category', 'writer'])
            ->where('show_in_scroll', 1)
            ->where('show_content', 1)
            ->latest()
            ->limit(10)
            ->get();

        return [
            'name' => 'Scroll News',
            'url'  => null,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch trending tag contents
     */
    private function getTrendingTagContents()
    {
        $position = BnManagePosition::where('position_type', 'trending_tag')->first();

        if (!$position || !$position->ids) {
            return [];
        }

        $tagIds = explode(',', $position->ids);
        $tags = Tag::whereIn('id', $tagIds)
            ->where('status', 1)
            ->get();

        $trendingTags = [];
        foreach ($tags as $tag) {
            $contents = $tag->contents()->with(['category', 'writer'])->latest()->limit(10)->get();
            $trendingTags[] = [
                'name' => $tag->name,
                'url'  => url('tags/' . $tag->alias),
                'contents' => BnContentResource::collection($contents)
            ];
        }

        return $trendingTags;
    }

    /**
     * Fetch recent contents
     */
    private function getRecentContents()
    {
        $contents = BnContent::with(['category', 'writer'])
            ->where('show_content', 1)
            ->latest()
            ->limit(10)
            ->get();

        return [
            'name' => 'Recent News',
            'url'  => null,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch popular contents (last 30 days)
     */
    private function getPopularContents()
    {
        $contents = BnContent::with(['category', 'writer'])
            ->leftJoin('bn_total_hits', 'bn_contents.id', '=', 'bn_total_hits.bn_content_id')
            ->where('bn_contents.created_at', '>=', now()->subDays(30))
            ->where('bn_contents.show_content', 1)
            ->orderByDesc('bn_total_hits.total_hits')
            ->limit(10)
            ->select('bn_contents.*')
            ->get();

        return [
            'name' => 'Popular News',
            'url'  => null,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch category home contents
     */
    private function getCategoryHomeContents($category)
    {
        $contents = $this->fetchPositionContents('category_home', $category->id);
        return [
            'name' => $category->name,
            //            'url'  => url($category->alias),
            'url'  => rtrim(config('app.frontend_url'), '/') . '/' . ($category->alias),
            'contents' => BnContentResource::collection($contents)
        ];
    }


    /**
     * Get star coming contents
     */
    private function getStarProgramContents()
    {
        $starProgram = StarProgram::where('status', 1)->first();

        return [
            'name' => 'Star Program',
            'contents' => $starProgram ? new StarProgramResource($starProgram) : null
        ];
    }

    /**
     * Get opinion contents
     */
    private function getOpinionContents($category)
    {
        $contents = $this->fetchPositionContents('category_home', $category->id);
        return [
            'name' => 'Opinion',
            'url'  => rtrim(config('app.frontend_url'), '/') . '/' . $category->alias,
            'contents' => sizeof($contents) > 0 ? $contents->map(
                fn($item) =>
                new BnContentResource($item, true, true)
            ) : []
        ];
    }

    /**
     * Internal helper to fetch contents for a given position
     */
    private function fetchPositionContents($type, $typeId = 0)
    {
        $position = BnManagePosition::where('position_type', $type)
            ->when($typeId, fn($q) => $q->where('position_type_id', $typeId))
            ->first();

        if ($position && $position->ids) {
            $ids = explode(',', $position->ids);
            $limit = $position->max_content ?? 10;
            $idsToFetch = array_slice($ids, 0, $limit);

            if (!empty($idsToFetch)) {
                $contents = BnContent::with(['category', 'writer'])
                    ->whereIn('id', $idsToFetch)
                    ->orderByRaw("FIELD(id, " . implode(',', $idsToFetch) . ")")
                    ->get();

                return BnContentResource::collection($contents);
            }
        }

        return [];
    }

    /**
     * Fetch latest active special event
     */
    private function getSpecialEvent()
    {
        $event = BnManageSpecialEvent::where('active_status', 1)
            ->latest('id')
            ->first();

        if ($event && $event->news_ids) {
            $ids = array_map('trim', explode(',', $event->news_ids));
            $contents = BnContent::with(['category', 'writer'])
                ->whereIn('id', $ids)
                ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                ->get();

            return [
                'name'     => $event->event_name,
                'url'      => $event->event_url,
                'contents' => BnContentResource::collection($contents)
            ];
        }

        return null;
    }
    /**
     * Get latest or popular contents
     */
    public function getLatestOrPopular(Request $request)
    {
        $types = $request->include ? explode(',', $request->include) : ['latest', 'popular'];
        $data = [];

        if (in_array('latest', $types)) {
            $data['recent'] = $this->getRecentContents();
        }

        if (in_array('popular', $types)) {
            $data['popular'] = $this->getPopularContents();
        }

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Get photo contents
     */
    private function getPhotoContents()
    {
        $albums = PhotoAlbum::where('status', 1)
            ->latest()
            ->limit(6)
            ->get();

        return [
            'name' => 'Photo Gallery',
            'url'  => url('photos'),
            'contents' => PhotoAlbumResource::collection($albums)
        ];
    }

    /**
     * Get video contents
     */
    private function getVideoContents()
    {
        $videos = Video::where('status', 1)
            ->latest()
            ->limit(8)
            ->get();

        return [
            'name' => 'Video Gallery',
            'url'  => url('videos'),
            'contents' => VideoResource::collection($videos)
        ];
    }

    /**
     * Get home video contents
     */
    private function getHomeVideoContents()
    {
        $videos = HomeVideoSection::where('status', 1)
            ->latest()
            ->get();

        return [
            'name' => 'Home Video Section',
            'contents' => HomeVideoSectionResource::collection($videos)
        ];
    }

    /**
     * Fetch subcategory home contents (latest 5)
     */
    private function getSubCategoryHomeContents($subCategory)
    {
        $contents = $subCategory->contents()
            ->with(['category', 'writer'])
            ->where('show_content', 1)
            ->latest()
            ->limit(5)
            ->get();

        return [
            'name' => $subCategory->name,
            'url'  => rtrim(config('app.frontend_url'), '/') . '/' . $subCategory->category->alias . '/' . $subCategory->alias,
            'contents' => BnContentResource::collection($contents)
        ];
    }

    /**
     * Fetch tag home contents (latest 5)
     */
    private function getTagHomeContents($tag)
    {
        $contents = $tag->contents()
            ->with(['category', 'writer'])
            ->where('show_content', 1)
            ->latest()
            ->limit(5)
            ->get();

        return [
            'name' => $tag->name,
            'url'  => url('tags/' . $tag->alias),
            'contents' => BnContentResource::collection($contents)
        ];
    }
}
