<?php

use App\Http\Controllers\API\ElectionController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\RamadanTimeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\DateTimeController;
use App\Http\Controllers\API\BnUpazilaController;
use App\Http\Controllers\API\BnScrollController;
use App\Http\Controllers\API\BnContentController;
use App\Http\Controllers\API\BnCategoryController;
use App\Http\Controllers\API\BnDistrictController;
use App\Http\Controllers\API\BnDivisionController;
use App\Http\Controllers\API\BnSubcategoryController;
use App\Http\Controllers\API\BnHomeController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\WriterController;

Route::prefix('v1')->middleware('restrict_domain')->group(function () {
    // Used by uptime monitoring. It verifies that both Laravel and MySQL are available.
    Route::get('/health', function () {
        DB::select('select 1');

        return response()->json([
            'success' => true,
            'status' => 'ok',
        ]);
    });

    // Bangla Date & Time API
    Route::get('/datetime-bn', [DateTimeController::class, 'index']);

    // Breaking News API
    Route::get('/breaking-news', [BnScrollController::class, 'index']);

    // Categories
    Route::get('/categories', [BnCategoryController::class, 'getCategories']);
    Route::get('/categories/{id}', [BnCategoryController::class, 'getCategory'])->where('id', '[0-9]+');
    Route::get('/categories/slug/{slug}', [BnCategoryController::class, 'getCategoryBySlug'])->where('slug', '[a-z-]+');
    Route::get('/categories/{id}/subcategories', [BnCategoryController::class, 'getSubcategories'])->where('id', '[0-9]+');
    Route::get('/categories/slug/{slug}/subcategories', [BnCategoryController::class, 'getSubcategoriesBySlug'])->where('slug', '[a-z-]+');

    // Subcategories
    Route::get('/subcategories/{id}', [BnSubcategoryController::class, 'getSubcategory'])->where('id', '[0-9]+');
    Route::get('/subcategories/slug/{slug}', [BnSubcategoryController::class, 'getSubCategoryBySlug'])->where('slug', '[a-z-]+');
    Route::get('/subcategories/slug/{catSlug}/{subSlug}', [BnSubcategoryController::class, 'getSubCategoryBySlug'])->where(['catSlug' => '[a-z-]+', 'subSlug' => '[a-z-]+']);

    // Divisions & Districts
    Route::get('/divisions', [BnDivisionController::class, 'getDivisions']);
    Route::get('/divisions/{id}', [BnDivisionController::class, 'getDivision'])->where('id', '[0-9]+');
    Route::get('/divisions/slug/{slug}', [BnDivisionController::class, 'getDivisionBySlug'])->where('slug', '[a-z-]+');
    Route::get('/divisions/{id}/districts', [BnDivisionController::class, 'getDistricts'])->where('id', '[0-9]+');
    Route::get('/divisions/slug/{slug}/districts', [BnDivisionController::class, 'getDistrictsBySlug'])->where('slug', '[a-z-]+');

    Route::get('/districts', [BnDistrictController::class, 'getDistricts']);
    Route::get('/districts/{id}', [BnDistrictController::class, 'getDistrict'])->where('id', '[0-9]+');
    Route::get('/districts/slug/{slug}', [BnDistrictController::class, 'getDistrictBySlug'])->where('slug', '[a-z-]+');
    Route::get('/districts/{id}/upazilas', [BnDistrictController::class, 'getUpazilas'])->where('id', '[0-9]+');
    Route::get('/districts/slug/{slug}/upazilas', [BnDistrictController::class, 'getUpazilasBySlug'])->where('slug', '[a-z-]+');

    Route::get('/upazilas/{id}', [BnUpazilaController::class, 'getUpazila'])->where('id', '[0-9]+');
    Route::get('/upazilas/slug/{slug}', [BnUpazilaController::class, 'getUpazilaBySlug'])->where('slug', '[a-z-]+');
    Route::get('/upazilas/slug/{districtSlug}/{upazilaSlug}', [BnUpazilaController::class, 'getUpazilaByDistrictAndSlug'])->where(['districtSlug' => '[a-z-]+', 'upazilaSlug' => '[a-z-]+']);

    // Tags
    Route::get('/tags', [TagController::class, 'getTags']);
    Route::get('/tags/{id}', [TagController::class, 'getTag'])->where('id', '[0-9]+');
    Route::get('/tags/slug/{slug}', [TagController::class, 'getTagBySlug']);

    // Writers
    Route::get('/writers', [WriterController::class, 'getWriters']);
    Route::get('/writers/{id}', [WriterController::class, 'getWriter'])->where('id', '[0-9]+');
    Route::get('/writers/slug/{slug}', [WriterController::class, 'getWriterBySlug']);

    // Contents APIs
    Route::get('/contents', [BnContentController::class, 'getContents']);
    Route::get('/contents/latest-popular', [BnHomeController::class, 'getLatestOrPopular']);
    Route::get('/contents/{id}', [BnContentController::class, 'getContent']);
    Route::get('/home', [BnHomeController::class, 'index']);

    // Photo Gallery
    Route::get('/photos', [App\Http\Controllers\API\PhotoController::class, 'index']);
    Route::get('/photos/category/{id}', [App\Http\Controllers\API\PhotoController::class, 'categoryAlbums'])->where('id', '[0-9]+');
    Route::get('/photos/{id}', [App\Http\Controllers\API\PhotoController::class, 'details'])->where('id', '[0-9]+');

    // Video Gallery
    Route::get('/videos', [App\Http\Controllers\API\VideoController::class, 'index']);
    Route::get('/videos/{id}', [App\Http\Controllers\API\VideoController::class, 'details'])->where('id', '[0-9]+');

    // Archive APIs
    Route::get('/archive', [BnContentController::class, 'getArchive']);
    Route::get('/content-search', [BnContentController::class, 'contentSearch']);

    // Menu APIs
    Route::get('/menu', [MenuController::class, 'index']);

    // Election APIs
    Route::get('/election', [ElectionController::class, 'electionData']);

    // Ramadan APIs
    Route::get('/ramadan', [RamadanTimeController::class, 'index']);

    // Sitemap APIs
    Route::get('/sitemaps', [\App\Http\Controllers\API\SitemapController::class, 'index']);
    Route::get('/sitemaps/news', [\App\Http\Controllers\API\SitemapController::class, 'news']);
    Route::get('/sitemaps/categories', [\App\Http\Controllers\API\SitemapController::class, 'categories']);
    Route::get('/sitemaps/sub-categories', [\App\Http\Controllers\API\SitemapController::class, 'subcategories']);
    Route::get('/sitemaps/topics', [\App\Http\Controllers\API\SitemapController::class, 'topics']);
    Route::get('/sitemaps/videos', [\App\Http\Controllers\API\SitemapController::class, 'videos']);
    Route::get('/sitemaps/photos', [\App\Http\Controllers\API\SitemapController::class, 'photos']);
    Route::get('/sitemaps/category/{slug}', [\App\Http\Controllers\API\SitemapController::class, 'categoryArticles'])->where('slug', '[a-z-]+');
});
