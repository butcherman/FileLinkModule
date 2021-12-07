<?php

return [
    /*
    *   Basic Module Information - Name and Version
    */
    'name' => 'FileLinkModule',
    'ver'  => '1.0.0-dev',

    /*
    *   Information for the Navbar
    */
    'navbar' => [
        [
            'perm_name' => 'Use File Links',
            'name'      => 'File Links',
            'icon'      => 'fas fa-link',
            'route'     => 'FileLinkModule.index',
        ],
    ],

    /**
     * If you would rather have a button link on the Dashboard page, use this section
     */
//    'dashboard_tool' => [
//        [
//            'name'  => 'Testing Module Tool',
//            'icon'  => 'far fa-smile',
//            'route' => 'TestingModule.index',
//        ],
//    ],

    /*
    *   Information for the Administration home page navigation links
    */
    'admin_nav' => [
        [
            'perm_name' => 'Manage File Links',
            'name'      => 'Manage File Links',
            'icon'      => 'fas fa-tools',
            'route'     => 'FileLinkModule.index',
        ],
    ],

    /*
    *   Notes if there are migrations that need to be added to the database
    */
    'has_migrations' => true,

    /*
    *   Location for uploaded files
    */
    'disks' => [
       'fileLinks' => [
           'driver' => 'local',
           'root'   => storage_path('app/file_links'),
       ],
    ],
];
