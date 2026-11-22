<?php

use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\RamadanTimeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\WriterController;
use App\Http\Controllers\Admin\AdminApiController;
use App\Http\Controllers\Admin\BnScrollController;
use App\Http\Controllers\Admin\TotalHitController;
use App\Http\Controllers\Admin\BdUpazilaController;
use App\Http\Controllers\Admin\BnContentController;
use App\Http\Controllers\Admin\MediaFileController;
use App\Http\Controllers\Admin\BdDistrictController;
use App\Http\Controllers\Admin\BdDivisionController;
use App\Http\Controllers\Admin\BnCategoryController;
use App\Http\Controllers\Admin\EpaperPageController;
use App\Http\Controllers\Admin\PhotoAlbumController;
use App\Http\Controllers\Admin\StarProgramController;
use App\Http\Controllers\Admin\BnSubCategoryController;
use App\Http\Controllers\Admin\PhotoCategoryController;
use App\Http\Controllers\Admin\BnManagePositionController;
use App\Http\Controllers\Admin\HomeVideoSectionController;
use App\Http\Controllers\Admin\BnSpecialCategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReportController;

// Redirect to login page initially
Route::get('/', function () {
    return redirect('/admin');
});

/**
 * Before Login - Admin routes
 */
Route::prefix('/admin')->middleware('restrict_admin_domain')->group(function () {
    Route::get('/', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

/**
 * After Login - Dashboard routes
 */
Route::prefix('/dashboard')->middleware('restrict_admin_domain')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('adminDashboard');

        // Only SuperAdmin can access the following routes
        Route::middleware('role:1')->group(function () {
            // User routes
            Route::resource('user', UserController::class);
            Route::prefix('/user')->group(function () {
                Route::get('{user}/change-password', [UserController::class, 'userPasswordEdit'])->name('userPasswordEdit');
                Route::post('{id}/change-password', [UserController::class, 'userPasswordUpdate'])->name('userPasswordUpdate');
                Route::post('{user}/restore', [UserController::class, 'userRestore'])->name('userRestore');
            });
        });

        // Bn content routes
        Route::prefix('/bn-content')->group(function () {
            // Total Hits routes
            Route::get('total-hits', [TotalHitController::class, 'bnContentTopHitList'])->name('bnContentTopHitList');

            // Content cancel editing route
            Route::get('{bn_content}/cancel', [BnContentController::class, 'cancel'])->name('bn-content.cancel');

            // Only for editable list
            Route::get('uneditable-list', [BnContentController::class, 'uneditableList'])->name('uneditableList');
            Route::post('{bn_content}/cancel-editable', [BnContentController::class, 'cancelEditing'])->name('cancelEditing');

            // Only SuperAdmin and SeniorSubEditor can Publish/Unpublish the content
            Route::middleware('role:1,6')->group(function () {
                Route::post('{id}/toggle-status', [BnContentController::class, 'toggleContentStatus'])->name('bn-content.toggle-status'); // Publish = 1, Unpublish = 2
            });

            // Only SuperAdmin can view content history
            Route::middleware('role:1')->group(function () {
                // Content history route
                Route::get('{id}/history', [\App\Http\Controllers\Admin\HistoryController::class, 'show'])->name('bn-content.history');
                Route::get('{id}/summary', [\App\Http\Controllers\Admin\HistoryController::class, 'summary'])->name('bn-content.summary');
            });

            // CKEditor image upload route
            Route::post('cke-upload', [BnContentController::class, 'ckeUpload'])->name('cke-upload');
        });
        // Rest of the bn-content routes
        Route::resource('bn-content', BnContentController::class);

        // Only SuperAdmin can access Category, SubCategory, SpecialCategory the content
        Route::middleware('role:1')->group(function () {
            // Category routes
            Route::resource('category', BnCategoryController::class);

            // Sub Category routes
            Route::resource('sub-category', BnSubCategoryController::class);

            // Special Category routes
            Route::resource('special-category', BnSpecialCategoryController::class);

            // Menu management routes
            Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
            Route::post('menu-save', [MenuController::class, 'saveMenu'])->name('menu.save');
        });

        // BD Division routes
        Route::resource('division', BdDivisionController::class);

        // BD District routes
        Route::resource('district', BdDistrictController::class);

        // BD Upazila routes
        Route::resource('upazila', BdUpazilaController::class);

        // Writer routes (role 1 or 6 only) 1=Admin, 6=Senior Sub Editor
        Route::middleware('role:1,6')->group(function () {
            Route::resource('writer', WriterController::class);
        });

        // Media File routes (for uploading images, videos, etc.)
        Route::resource('media-file', MediaFileController::class)->only(['index', 'create', 'store']);

        // Epaper Page routes
        // Route::resource('epaper-page', EpaperPageController::class)->only(['index', 'create', 'store', 'edit', 'update']);

        // Poll routes
        // Route::resource('poll', PollController::class);

        // Video routes
        Route::resource('video', VideoController::class);

        // Webstory Category routes
        // Route::resource('webstory-category', WebstoryCategoryController::class);

        // Webstory routes
        // Route::resource('webstory', WebstoryController::class);

        // Photo Category routes
        Route::resource('photo-category', PhotoCategoryController::class);

        // Photo Album routes
        Route::resource('photo-album', PhotoAlbumController::class);

        // Scroll routes (Breaking, General Scroll)
        Route::resource('bn-scroll', BnScrollController::class);

        // Admin panel APIs routes
        Route::get('tag/search', [AdminApiController::class, 'tagSearch'])->name('tagSearch');
        Route::get('get-subcategories', [AdminApiController::class, 'getSubcategoriesByCategory'])->name('getSubcategoriesByCategory');
        Route::get('get-upazilas', [AdminApiController::class, 'getUpazilaByDistrict'])->name('getUpazilaByDistrict');
        Route::post('tag-store', [AdminApiController::class, 'tagStore'])->name('tagStoreApi');

        // Tag and Writer routes (role 1 or 6 only) 1=Admin, 6=Senior Sub Editor
        Route::middleware('role:1,6')->group(function () {
            Route::resource('tag', TagController::class);
        });

        // Elections routes
        Route::get('/national-election-2026', [ElectionController::class, 'nationalElection2026'])->name('nationalElection2026');
        Route::post('/national-election-2026/update', [ElectionController::class, 'nationalElection2026Update'])->name('nationalElection2026Update');

        // Ramadan times routes
        Route::get('/ramadan-time', [RamadanTimeController::class, 'index'])->name('ramadan.index');
        Route::post('/ramadan-time', [RamadanTimeController::class, 'update'])->name('ramadan.update');

        // Manage Position routes
        Route::get('manage-position/search', [BnManagePositionController::class, 'searchContent'])->name('manage-position.search');
        Route::get('manage-position/search-tag', [BnManagePositionController::class, 'searchTag'])->name('manage-position.search-tag');

        Route::prefix('manage-position')->name('manage-position.')->group(function () {
            Route::get('lead', [BnManagePositionController::class, 'lead'])->name('lead');
            Route::get('special', [BnManagePositionController::class, 'special'])->name('special');
            Route::get('special-event', [BnManagePositionController::class, 'specialEvent'])->name('special-event');
            Route::get('special-event/create', [BnManagePositionController::class, 'createSpecialEvent'])->name('special-event.create');
            Route::post('special-event/{id}/toggle-status', [BnManagePositionController::class, 'toggleSpecialEventStatus'])->name('special-event.toggle-status');
            Route::get('special-event/{id}/edit', [BnManagePositionController::class, 'editSpecialEvent'])->name('special-event.edit');
            Route::get('category-combined', [BnManagePositionController::class, 'categoryCombined'])->name('category-combined');
            Route::get('category-home', [BnManagePositionController::class, 'categoryHome'])->name('category-home');
            Route::get('category-inner', [BnManagePositionController::class, 'categoryInner'])->name('category-inner');
            Route::get('scroll', [BnManagePositionController::class, 'scroll'])->name('scroll');
            Route::get('trending-tag', [BnManagePositionController::class, 'trendingTag'])->name('trending-tag');
        });
        Route::resource('home-video-section', HomeVideoSectionController::class);
        Route::resource('star-program', StarProgramController::class);
        Route::resource('manage-position', BnManagePositionController::class);

        // Report routes
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{user}', [ReportController::class, 'show'])->name('show');
            Route::get('/{user}/export-pdf', [ReportController::class, 'exportUserPdf'])->name('export-user-pdf');
            Route::get('/{user}/contents', [ReportController::class, 'userContents'])->name('contents');
        });

        // Admin profile, change-password, edit-profile, change-photo routes
        Route::get('profile', [AdminController::class, 'adminProfile'])->name('adminProfile');
        Route::get('change-password', [AdminController::class, 'adminChangePassword'])->name('adminChangePassword');
        Route::post('change-password', [AdminController::class, 'adminUpdatePassword'])->name('adminUpdatePassword');
        Route::get('edit-profile', [AdminController::class, 'adminEditProfile'])->name('adminEditProfile');
        Route::post('edit-profile', [AdminController::class, 'adminUpdateProfile'])->name('adminUpdateProfile');
        Route::get('change-photo', [AdminController::class, 'adminChangePhoto'])->name('adminChangePhoto');
        Route::post('change-photo', [AdminController::class, 'adminUpdatePhoto'])->name('adminUpdatePhoto');

        // Logout
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
