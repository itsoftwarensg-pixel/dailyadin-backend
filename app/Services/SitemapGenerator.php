<?php

namespace App\Services;

use App\Models\BnCategory;
use App\Models\BnSubCategory;
use App\Models\BnContent;
use App\Models\Tag;
use App\Models\Video;
use App\Models\PhotoAlbum;
use App\Enums\StatusEnum;

class SitemapGenerator
{
    /**
     * Helper to get the frontend base URL.
     */
    protected static function getFrontendUrl()
    {
        return rtrim(env('FRONTEND_URL', 'https://dailyadin.com'), '/');
    }

    /**
     * Helper to format datetime to W3C / ISO 8601 format.
     */
    protected static function formatTimestamp($datetime)
    {
        if (!$datetime) {
            return now()->toIso8601String();
        }
        return \Illuminate\Support\Carbon::parse($datetime)->toIso8601String();
    }

    /**
     * Get the sitemap index data.
     */
    public static function getIndexData()
    {
        $sitemaps = [];
        $baseUrl = self::getFrontendUrl();

        // 1. News Sitemap (using latest news created_at)
        $latestNews = BnContent::select('created_at')
            ->where('show_content', StatusEnum::ACTIVE->value)
            ->latest('updated_at')
            ->first();
        $sitemaps[] = [
            'loc' => $baseUrl . '/sitemaps/news-sitemap.xml',
            'lastmod' => self::formatTimestamp($latestNews?->created_at ?? now())
        ];

        // 2. Category Sitemap
        $latestCategory = BnCategory::select('updated_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('updated_at')
            ->first();
        $sitemaps[] = [
            'loc' => $baseUrl . '/sitemaps/category-sitemap.xml',
            'lastmod' => self::formatTimestamp($latestCategory?->updated_at ?? now())
        ];

        // 3. Sub-category Sitemap
        $latestSubCategory = BnSubCategory::select('updated_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('updated_at')
            ->first();
        $sitemaps[] = [
            'loc' => $baseUrl . '/sitemaps/sub-category-sitemap.xml',
            'lastmod' => self::formatTimestamp($latestSubCategory?->updated_at ?? now())
        ];

        // 4. Topic Sitemap
        $latestTopic = Tag::select('updated_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('updated_at')
            ->first();
        $sitemaps[] = [
            'loc' => $baseUrl . '/sitemaps/topic-sitemap.xml',
            'lastmod' => self::formatTimestamp($latestTopic?->updated_at ?? now())
        ];

        // 5. Video Sitemap (using latest video created_at)
        $latestVideo = Video::select('created_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('created_at')
            ->first();
        $sitemaps[] = [
            'loc' => $baseUrl . '/sitemaps/video-sitemap.xml',
            'lastmod' => self::formatTimestamp($latestVideo?->created_at ?? now())
        ];

        // 6. Photo Sitemap (using latest photo album created_at)
        $latestPhoto = PhotoAlbum::select('created_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('created_at')
            ->first();
        $sitemaps[] = [
            'loc' => $baseUrl . '/sitemaps/photo-sitemap.xml',
            'lastmod' => self::formatTimestamp($latestPhoto?->created_at ?? now())
        ];

        // 7. Category-specific Article Sitemaps (using latest article created_at in each category)
        $categories = BnCategory::select('id', 'alias')
            ->where('status', StatusEnum::ACTIVE->value)
            ->get();
        foreach ($categories as $category) {
            $latestArticle = BnContent::select('created_at')
                ->where('show_content', StatusEnum::ACTIVE->value)
                ->byCategory($category->id)
                ->latest('created_at')
                ->first();

            if ($latestArticle) {
                $sitemaps[] = [
                    'loc' => $baseUrl . '/' . $category->alias . '/sitemap.xml',
                    'lastmod' => self::formatTimestamp($latestArticle->created_at)
                ];
            }
        }

        return $sitemaps;
    }

    /**
     * Get news sitemap data (latest 100 articles).
     */
    public static function getNewsData()
    {
        $contents = BnContent::select('id', 'category_id', 'created_at', 'heading')
            ->with(['category' => function ($query) {
                $query->select('id', 'alias');
            }])
            ->where('show_content', StatusEnum::ACTIVE->value)
            ->latest('id')
            ->limit(100)
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($contents as $content) {
            $categorySlug = $content->category->alias ?? 'news';
            $data[] = [
                'loc' => $baseUrl . '/' . $categorySlug . '/' . $content->id,
                'publication_date' => self::formatTimestamp($content->created_at),
                'title' => $content->heading
            ];
        }
        return $data;
    }

    /**
     * Get categories sitemap data.
     */
    public static function getCategoriesData()
    {
        $categories = BnCategory::select('alias')
            ->where('status', StatusEnum::ACTIVE->value)
            ->orderBy('sorting', 'asc')
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($categories as $category) {
            $data[] = [
                'loc' => $baseUrl . '/' . $category->alias
            ];
        }
        return $data;
    }

    /**
     * Get subcategories sitemap data.
     */
    public static function getSubcategoriesData()
    {
        $subcategories = BnSubCategory::select('alias', 'category_id')
            ->with(['category' => function ($query) {
                $query->select('id', 'alias');
            }])
            ->where('status', StatusEnum::ACTIVE->value)
            ->orderBy('sorting', 'asc')
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($subcategories as $sub) {
            if ($sub->category) {
                $data[] = [
                    'loc' => $baseUrl . '/' . $sub->category->alias . '/' . $sub->alias
                ];
            }
        }
        return $data;
    }

    /**
     * Get topics (tags) sitemap data.
     */
    public static function getTopicsData()
    {
        $topics = Tag::select('alias', 'updated_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->orderBy('sorting', 'asc')
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($topics as $topic) {
            $data[] = [
                'loc' => $baseUrl . '/topic/' . $topic->alias,
                'lastmod' => self::formatTimestamp($topic->updated_at)
            ];
        }
        return $data;
    }

    /**
     * Get videos sitemap data.
     */
    public static function getVideosData()
    {
        $videos = Video::select('id', 'updated_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('id')
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($videos as $video) {
            $data[] = [
                'loc' => $baseUrl . '/videos/' . $video->id,
                'lastmod' => self::formatTimestamp($video->updated_at)
            ];
        }
        return $data;
    }

    /**
     * Get photos (albums) sitemap data.
     */
    public static function getPhotosData()
    {
        $albums = PhotoAlbum::select('id', 'updated_at')
            ->where('status', StatusEnum::ACTIVE->value)
            ->latest('id')
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($albums as $album) {
            $data[] = [
                'loc' => $baseUrl . '/photos/' . $album->id,
                'lastmod' => self::formatTimestamp($album->updated_at)
            ];
        }
        return $data;
    }

    /**
     * Get category-specific articles sitemap data.
     */
    public static function getCategoryArticlesData(BnCategory $category)
    {
        $contents = BnContent::select('id', 'category_id', 'secondary_category_ids', 'updated_at')
            ->where('show_content', StatusEnum::ACTIVE->value)
            ->byCategory($category->id)
            ->latest('id')
            ->limit(100)
            ->get();

        $data = [];
        $baseUrl = self::getFrontendUrl();
        foreach ($contents as $content) {
            $data[] = [
                'loc' => $baseUrl . '/' . $category->alias . '/' . $content->id,
                'lastmod' => self::formatTimestamp($content->updated_at)
            ];
        }
        return $data;
    }
}
