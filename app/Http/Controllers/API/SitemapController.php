<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BnCategory;
use App\Services\SitemapGenerator;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Get sitemap index.
     */
    public function index()
    {
        $data = Cache::remember('sitemap_index', 120, function () {
            return SitemapGenerator::getIndexData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Sitemap index retrieved successfully'
        ]);
    }

    /**
     * Get news sitemap (latest 100 articles).
     */
    public function news()
    {
        $data = Cache::remember('sitemap_news', 120, function () {
            return SitemapGenerator::getNewsData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'News sitemap retrieved successfully'
        ]);
    }

    /**
     * Get categories sitemap.
     */
    public function categories()
    {
        $data = Cache::remember('sitemap_categories', 120, function () {
            return SitemapGenerator::getCategoriesData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Categories sitemap retrieved successfully'
        ]);
    }

    /**
     * Get sub-categories sitemap.
     */
    public function subcategories()
    {
        $data = Cache::remember('sitemap_subcategories', 120, function () {
            return SitemapGenerator::getSubcategoriesData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Sub-categories sitemap retrieved successfully'
        ]);
    }

    /**
     * Get topics (tags) sitemap.
     */
    public function topics()
    {
        $data = Cache::remember('sitemap_topics', 120, function () {
            return SitemapGenerator::getTopicsData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Topics sitemap retrieved successfully'
        ]);
    }

    /**
     * Get videos sitemap.
     */
    public function videos()
    {
        $data = Cache::remember('sitemap_videos', 120, function () {
            return SitemapGenerator::getVideosData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Videos sitemap retrieved successfully'
        ]);
    }

    /**
     * Get photos sitemap.
     */
    public function photos()
    {
        $data = Cache::remember('sitemap_photos', 120, function () {
            return SitemapGenerator::getPhotosData();
        });

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Photos sitemap retrieved successfully'
        ]);
    }

    /**
     * Get category-specific articles sitemap.
     */
    public function categoryArticles($slug)
    {
        $data = Cache::remember("sitemap_category_{$slug}", 120, function () use ($slug) {
            $category = BnCategory::select('id', 'alias', 'status')
                ->where('alias', $slug)
                ->where('status', StatusEnum::ACTIVE->value)
                ->first();

            if (!$category) {
                return null;
            }

            return SitemapGenerator::getCategoryArticlesData($category);
        });

        if ($data === null) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'data' => null,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $data,
            'message' => 'Category articles sitemap retrieved successfully'
        ]);
    }
}
