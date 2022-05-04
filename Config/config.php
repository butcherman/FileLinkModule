<?php

return [
    /*
    *   Basic Module Information - Name and Version
    */
    'name' => 'FileLinkModule',
    'ver'  => '1.0.2',

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

    /*
    *   Information for the Administration home page navigation links
    */
    'admin_nav' => [
        [
            'perm_name' => 'Manage File Links',
            'name'      => 'Manage File Links',
            'icon'      => 'fas fa-tools',
            'route'     => 'FileLinkModule.admin.index',
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
