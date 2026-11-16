<?php

return [
    'status' => [
        1 => [
            'id' => 1,
            'name' => 'Active',
            'color' => 'primary'
        ],
        2 => [
            'id' => 2,
            'name' => 'Inactive',
            'color' => 'danger'
        ]
    ],

    'yesNo' => [
        1 => [
            'id' => 1,
            'name' => 'Yes',
            'color' => 'primary'
        ],
        2 => [
            'id' => 2,
            'name' => 'No',
            'color' => 'danger'
        ]
    ],

    'video_categories' => [
        1 => 'নিউজ',
        2 => 'বিশ্ব',
        3 => 'বিনোদন',
    ],


    'roleColor' => [
        "success",
        "primary",
        "info",
        "secondary",
        "dark",
        "warning",
        "danger",
    ],

    'color' => [
        "success",
        "primary",
        "info",
        "secondary",
        "dark",
        "warning",
        "danger",
    ],

    'logoPath' => 'adminAssets/media/common/logo.png',
    'uploadPath' => 'uploads/',
    'uploadImagePath' => 'uploads/images/',
    'commonImagePath' => 'assets/images/common/',
    'permissionErrorMsg' => 'You are not permitted to delete the record. A message is sent to your super admin that you are trying to delete!. Please contact to your super admin to avoid further action.'
];
