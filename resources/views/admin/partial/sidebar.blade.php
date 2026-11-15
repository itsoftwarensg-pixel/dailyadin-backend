<!--- START Dynamic Menu Functionality --->
<?php
$currentUrl = url()->current();

//$menu = [
//    [
//        'header' => 'Content Area',
//        'groups' => [
//            [
//                'title' => 'Bangla',
//                'icon' => 'fa-file-text-o',
//                'items' => [
//                    ['title' => 'Add Content', 'url' => route('bn-content.create')],
//                    ['title' => 'All Content', 'url' => route('bn-content.index')],
//                    //                    ['title' => 'All Pages', 'url' => 'Settings/Bangla/pageList.php'],
//                    //                    ['title' => 'All Advertisement', 'url' => 'advertisement/advertisementList.php'],
//                    ['title' => 'Report', 'url' => 'admin/primary-report.php'],
//                    //                    ['title' => 'Top Hit Contents', 'url' => route('bnContentTopHitList')],
//                ],
//                'extra_match' => ['content/bangla/contentUpdate.php', 'Settings/Bangla/tagEdit.php', 'Settings/Bangla/pageEdit.php', 'advertisement/advertisementUpdate.php'],
//            ],
//            [
//                'title' => 'Scroll News',
//                'icon' => 'fa-bullhorn',
//                'items' => [['title' => 'Add New Scroll Item', 'url' => route('bn-scroll.create')], ['title' => 'All Scroll Items', 'url' => route('bn-scroll.index')]],
//                'extra_match' => ['scroll/scrollUpdate.php'],
//            ],
//            [
//                'title' => 'Tag',
//                'icon' => 'fa-tag',
//                'items' => [['title' => 'Add Tag', 'url' => route('tag.create')], ['title' => 'All Tags', 'url' => route('tag.index')]],
//            ],
//            [
//                'title' => 'Manage Positions',
//                'icon' => 'fa-arrows',
//                'items' => [['title' => 'Manage Lead News', 'url' => route('manage-position.lead')], ['title' => 'Manage Category Positions', 'url' => route('manage-position.category-combined')], ['title' => 'Manage Special Event', 'url' => route('manage-position.special-event')], ['title' => 'Manage Scroll position', 'url' => route('manage-position.scroll')], ['title' => 'Manage Trending Tags', 'url' => route('manage-position.trending-tag')]],
//                'extra_match' => ['scroll/scrollUpdate.php'],
//            ],
//        ],
//    ],
//    [
//        'header' => 'Media',
//        'groups' => [
//            // [
//            //     'title' => 'E-Paper',
//            //     'icon' => 'fa-newspaper-o',
//            //     'items' => [['title' => 'Update Epaper Page', 'url' => route('epaper-page.index')]],
//            // ],
//            [
//                'title' => 'Media File',
//                'icon' => 'fa-file-o',
//                'items' => [['title' => 'Add Media File', 'url' => route('media-file.create')], ['title' => 'All Media File', 'url' => route('media-file.index')]],
//            ],
//            [
//                'title' => 'Photo',
//                'icon' => 'fa-file-image-o',
//                'items' => [['title' => 'Add Photo Category', 'url' => route('photo-category.create')], ['title' => 'All Photo Categories', 'url' => route('photo-category.index')], ['title' => 'Add Photo Album', 'url' => route('photo-album.create')], ['title' => 'All Photo Albums', 'url' => route('photo-album.index')]],
//                'extra_match' => ['photo/photoCategoryUpdate.php', 'photo/albumUpdate.php', 'photo/photoUpdate.php'],
//            ],
//            [
//                'title' => 'Video',
//                'icon' => 'fa-video-camera',
//                'items' => [['title' => 'Add Video', 'url' => route('video.create')], ['title' => 'All Videos', 'url' => route('video.index')], ['title' => 'Top Video', 'url' => route('home-video-section.index')]],
//                'extra_match' => ['video/videoUpdate.php'],
//            ],
//            /*[
//                'title' => 'Poll',
//                'icon' => 'fa-check-square-o',
//                'items' => [['title' => 'Add Poll', 'url' => route('poll.create')], ['title' => 'All Poll', 'url' => route('poll.index')]],
//                'extra_match' => ['Poll/updatePollQuestion.php'],
//            ],
//            [
//                'title' => 'Webstory',
//                'icon' => 'fa-picture-o',
//                'items' => [['title' => 'Add Webstory', 'url' => route('webstory.create')], ['title' => 'All Webstory', 'url' => route('webstory.index')], ['title' => 'Add Category', 'url' => route('webstory-category.create')], ['title' => 'All Category', 'url' => route('webstory-category.index')]],
//                'extra_match' => ['category/Bangla/webstoryEdit.php', 'category/Bangla/categoryEdit.php'],
//            ],*/
//        ],
//    ],
//    [
//        'header' => 'Configuration Area',
//        'groups' => [
//            [
//                'title' => 'Configuration',
//                'icon' => 'fa-cogs',
//                'items' => [['title' => 'Add Category', 'url' => route('category.create')], ['title' => 'All Categories', 'url' => route('category.index')], ['title' => 'Add Sub Category', 'url' => route('sub-category.create')], ['title' => 'All Sub Categories', 'url' => route('sub-category.index')], ['title' => 'Add Special Category', 'url' => route('special-category.create')], ['title' => 'All Special Categories', 'url' => route('special-category.index')], ['title' => 'All Divisions', 'url' => route('division.index')], ['title' => 'All Districts', 'url' => route('district.index')], ['title' => 'All Upazilas', 'url' => route('upazila.index')]],
//                'extra_match' => ['Settings/Bangla/categoryEdit.php', 'Settings/Bangla/subCategoryEdit.php', 'Settings/Bangla/specialCategoryEdit.php'],
//            ],
//            [
//                'title' => 'Writer',
//                'icon' => 'fa-pencil',
//                'items' => [['title' => 'Add Writer', 'url' => route('writer.create')], ['title' => 'All Writers', 'url' => route('writer.index')]],
//                'extra_match' => ['people/editUser.php', 'people/writerUpdate.php'],
//            ],
//        ],
//    ]
//];
//
////if ($_SESSION['userType'] == 1) {
//if (auth()->user()->role_id == 1) {
//    $menu[] = [
//        'header' => 'Admin Area',
//        'groups' => [
////            [
////                'title' => 'Configuration',
////                'icon' => 'fa-cogs',
////                'items' => [['title' => 'Add Category', 'url' => route('category.create')], ['title' => 'All Categories', 'url' => route('category.index')], ['title' => 'Add Sub Category', 'url' => route('sub-category.create')], ['title' => 'All Sub Categories', 'url' => route('sub-category.index')], ['title' => 'Add Special Category', 'url' => route('special-category.create')], ['title' => 'All Special Categories', 'url' => route('special-category.index')], ['title' => 'All Divisions', 'url' => route('division.index')], ['title' => 'All Districts', 'url' => route('district.index')], ['title' => 'All Upazilas', 'url' => route('upazila.index')], ['title' => 'Monthly Folder Create', 'url' => 'Settings/createMonthlyImageFolder.php']],
////                'extra_match' => ['Settings/Bangla/categoryEdit.php', 'Settings/Bangla/subCategoryEdit.php', 'Settings/Bangla/specialCategoryEdit.php'],
////            ],
//            [
//                'title' => 'User Management',
//                'icon' => 'fa-users',
//                'items' => [['title' => 'Add User', 'url' => route('user.create')], ['title' => 'All Users', 'url' => route('user.index')]],
//                'extra_match' => ['people/editUser.php', 'people/writerUpdate.php'],
//            ],
//        ],
//    ];
//}
//

?>

<?php

$isAdmin = auth()->check() && auth()->user()->role_id == 1;
$canManageTagWriter = auth()->check() && in_array(auth()->user()->role_id, [1, 6]); // 1=Admin, 6=Senior Sub Editor

/*
|--------------------------------------------------------------------------
| CONTENT AREA
|--------------------------------------------------------------------------
*/
$menu = [
    [
        'header' => 'Content Area',
        'groups' => [
            [
                'title' => 'Bangla',
                'icon' => 'fa-file-text-o',
                'items' => array_filter([['title' => 'Add Content', 'url' => route('bn-content.create')], ['title' => 'All Content', 'url' => route('bn-content.index')]]),
            ],

            [
                'title' => 'Scroll News',
                'icon' => 'fa-bullhorn',
                'items' => [['title' => 'Add New Scroll Item', 'url' => route('bn-scroll.create')], ['title' => 'All Scroll Items', 'url' => route('bn-scroll.index')]],
            ],

            ...($canManageTagWriter ? [[
                'title' => 'Tag',
                'icon' => 'fa-tag',
                'items' => [['title' => 'Add Tag', 'url' => route('tag.create')], ['title' => 'All Tags', 'url' => route('tag.index')]],
            ]] : []),

            [
                'title' => 'Manage Positions',
                'icon' => 'fa-arrows',
                'items' => [['title' => 'Manage Lead News', 'url' => route('manage-position.lead')], ['title' => 'Manage Special News', 'url' => route('manage-position.special')], ['title' => 'Manage Category Positions', 'url' => route('manage-position.category-combined')], /*['title' => 'Manage Special Event', 'url' => route('manage-position.special-event')],*/ ['title' => 'Manage Scroll position', 'url' => route('manage-position.scroll')], ['title' => 'Manage Trending Tags', 'url' => route('manage-position.trending-tag')]],
            ],
        ],
    ],
];

/*
|--------------------------------------------------------------------------
| ELECTION AREA
|--------------------------------------------------------------------------
*/
$menu[] = [
    'header' => 'Election Area',
    'groups' => [
        [
            'title' => 'Election',
            'icon' => 'fa-check-square-o',
            'items' => [['title' => 'National Election 2026', 'url' => route('nationalElection2026')]],
        ],
    ],
];

/*
|--------------------------------------------------------------------------
| MEDIA AREA
|--------------------------------------------------------------------------
*/
$menu[] = [
    'header' => 'Media',
    'groups' => [
        [
            'title' => 'Media File',
            'icon' => 'fa-file-o',
            'items' => [['title' => 'Add Media File', 'url' => route('media-file.create')], ['title' => 'All Media File', 'url' => route('media-file.index')]],
        ],

        [
            'title' => 'Photo',
            'icon' => 'fa-file-image-o',
            'items' => [['title' => 'Add Photo Album', 'url' => route('photo-album.create')], ['title' => 'All Photo Albums', 'url' => route('photo-album.index')], ['title' => 'Add Photo Category', 'url' => route('photo-category.create')], ['title' => 'All Photo Categories', 'url' => route('photo-category.index')]],
        ],

        [
            'title' => 'Video',
            'icon' => 'fa-video-camera',
            'items' => [['title' => 'Add Video', 'url' => route('video.create')], ['title' => 'All Videos', 'url' => route('video.index')], ['title' => 'Top Video', 'url' => route('home-video-section.index')], ['title' => 'Star Program', 'url' => route('star-program.index')]],
        ],
    ],
];

/*
|--------------------------------------------------------------------------
| CONFIGURATION AREA (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
$menu[] = [
    'header' => 'Configuration Area',
    'groups' => [
        [
            'title' => 'Configuration',
            'icon' => 'fa-cogs',
            'items' => array_filter([$isAdmin ? ['title' => 'Add Category', 'url' => route('category.create')] : null, $isAdmin ? ['title' => 'All Categories', 'url' => route('category.index')] : null, $isAdmin ? ['title' => 'Add Sub Category', 'url' => route('sub-category.create')] : null, $isAdmin ? ['title' => 'All Sub Categories', 'url' => route('sub-category.index')] : null, $isAdmin ? ['title' => 'Add Special Category', 'url' => route('special-category.create')] : null, $isAdmin ? ['title' => 'All Special Categories', 'url' => route('special-category.index')] : null, $isAdmin ? ['title' => 'Menu Management', 'url' => route('menu.index')] : null, ['title' => 'All Divisions', 'url' => route('division.index')], ['title' => 'All Districts', 'url' => route('district.index')], ['title' => 'All Upazilas', 'url' => route('upazila.index')]]),
        ],

        ...($canManageTagWriter ? [[
            'title' => 'Writer',
            'icon' => 'fa-pencil',
            'items' => [['title' => 'Add Writer', 'url' => route('writer.create')], ['title' => 'All Writers', 'url' => route('writer.index')]],
        ]] : []),
    ],
];

/*
|--------------------------------------------------------------------------
| ADMIN AREA (USER MANAGEMENT)
|--------------------------------------------------------------------------
*/
if ($isAdmin) {
    $menu[] = [
        'header' => 'Admin Area',
        'groups' => [
            [
                'title' => 'User Management',
                'icon' => 'fa-users',
                'items' => [['title' => 'Add User', 'url' => route('user.create')], ['title' => 'All Users', 'url' => route('user.index')]],
            ],
            [
                'title' => 'Reports',
                'icon' => 'fa-bar-chart',
                'items' => [['title' => 'User Reports', 'url' => route('report.index')]],
            ],
        ],
    ];
}

?>


<!--- END Dynamic Menu Functionality --->
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel" style="min-height:64px">
            <div class="pull-left image">
                @if (auth()->user()->photo)
                    <img src="{{ asset(Config::get('helper.uploadPath') . auth()->user()->photo) }}" class="img-circle"
                        alt="{{ auth()->user()->name }}" style="height: 45px; width: 45px;">
                @else
                    <img src="{{ asset('adminAssets/no_image.png') }}" class="img-circle" alt="No Image">
                @endif
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard text-green"></i><span>Back to
                        Dashboard</span></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">

            <!--- START Dynamic Menu --->
            <?php foreach ($menu as $section): ?>
            <li class="header text-uppercase"><strong><?= $section['header'] ?></strong></li>
            <?php foreach ($section['groups'] as $group): ?>
                <?php
                $isGroupActive = false;
                foreach ($group['items'] as $item) {
                    if ($currentUrl === $item['url'] || str_starts_with($currentUrl, rtrim($item['url'], '/') . '/')) {
                        $isGroupActive = true;
                        break;
                    }
                }
                if (!$isGroupActive && isset($group['extra_match'])) {
                    foreach ($group['extra_match'] as $extra) {
                        if (strpos($_SERVER['PHP_SELF'], '/' . $extra) !== false) {
                            $isGroupActive = true;
                            break;
                        }
                    }
                }
                ?>
                <li class="treeview <?= $isGroupActive ? 'active menu-open' : '' ?>">
                    <a href="javascript:">
                        <i class="fa <?= $group['icon'] ?>"></i> <span><?= $group['title'] ?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu" style="<?= $isGroupActive ? 'display:block;' : '' ?>">
                        <?php foreach ($group['items'] as $item): ?>
                        <?php $isActive = $currentUrl === $item['url'] || str_starts_with($currentUrl, rtrim($item['url'], '/') . '/'); ?>
                        <li class="<?= $isActive ? 'active' : '' ?>">
                            <a href="<?= $item['url'] ?>">
                                <i class="fa fa-dot-circle-o"></i> <span><?= $item['title'] ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul><!-- /.sidebar-menu -->
    </section>
</aside>


{{-- @php
    use Illuminate\Support\Facades\Route;

    $currentRouteName = Route::currentRouteName();

    $menu = [
        [
            'header' => 'Content Area',
            'groups' => [
                [
                    'title' => 'Bangla',
                    'icon' => 'fa-file-text-o',
                    'items' => [
                        ['title' => 'Add Content', 'route' => 'bn-content.create'],
                        ['title' => 'All Content', 'route' => 'bn-content.index'],
//                        ['title' => 'Report', 'route' => ''],
                    ],
                    'extra_match' => ['bn-content.edit'],
                ],
                [
                    'title' => 'Scroll News',
                    'icon' => 'fa-bullhorn',
                    'items' => [
                        ['title' => 'Add New Scroll Item', 'route' => 'bn-scroll.create'],
                        ['title' => 'All Scroll Items', 'route' => 'bn-scroll.index'],
                    ],
                    'extra_match' => ['bn-scroll.edit'],
                ],
                [
                    'title' => 'Tag',
                    'icon' => 'fa-tag',
                    'items' => [
                        ['title' => 'Add Tag', 'route' => 'tag.create'],
                        ['title' => 'All Tags', 'route' => 'tag.index'],
                    ],
                    'extra_match' => ['tag.edit'],
                ],
            ]
        ],
        [
            'header' => 'Media',
            'groups' => [
                [
                    'title' => 'E-Paper',
                    'icon' => 'fa-newspaper-o',
                    'items' => [
                        ['title' => 'Update Epaper Page', 'route' => 'epaper-page.index'],
                    ]
                ],
                [
                    'title' => 'Media File',
                    'icon' => 'fa-file-o',
                    'items' => [
                        ['title' => 'Add Media File', 'route' => 'media-file.create'],
                        ['title' => 'All Media File', 'route' => 'media-file.index'],
                    ]
                ],
            ]
        ],
    ];

    if(auth()->user()->role_id == 1){
        $menu[] = [
            'header' => 'Admin Area',
            'groups' => [
                [
                    'title' => 'Configuration',
                    'icon' => 'fa-cogs',
                    'items' => [
                        ['title' => 'Add Category', 'route' => 'category.create'],
                        ['title' => 'All Categories', 'route' => 'category.index'],
                        ['title' => 'Add Sub Category', 'route' => 'sub-category.create'],
                        ['title' => 'All Sub Categories', 'route' => 'sub-category.index'],
                    ],
                    'extra_match' => ['category.edit', 'sub-category.edit'],
                ],
            ]
        ];
    }

@endphp

<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel" style="min-height:64px">
            <div class="pull-left image">
                <img src="{{ auth()->user()->photo ? asset(Config::get('helper.uploadPath') . auth()->user()->photo) : asset('adminAssets/no_image.png') }}"
                     class="img-circle" alt="{{ auth()->user()->name }}" style="height:45px;width:45px;">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard text-green"></i> <span>Back to Dashboard</span></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            @foreach ($menu as $section)
                <li class="header text-uppercase"><strong>{{ $section['header'] }}</strong></li>
                @foreach ($section['groups'] as $group)
                    @php
                        $groupRoutes = collect($group['items'])->pluck('route')->toArray();
                        $groupActive = in_array($currentRouteName, $groupRoutes)
                                       || (isset($group['extra_match']) && in_array($currentRouteName, $group['extra_match']));
                    @endphp
                    <li class="treeview {{ $groupActive ? 'active' : '' }}">
                        <a href="javascript:">
                            <i class="fa {{ $group['icon'] }}"></i> <span>{{ $group['title'] }}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu" style="{{ $groupActive ? 'display:block;' : 'display:none;' }}">
                            @foreach ($group['items'] as $item)
                                <li class="{{ $currentRouteName === $item['route'] ? 'active' : '' }}">
                                    <a href="{{ route($item['route']) }}">
                                        <i class="fa fa-dot-circle-o"></i> <span>{{ $item['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </section>
</aside> --}}
