<?php

return [
    'name'                    => 'Backup',
    'backup_description'      => 'Backup database and uploads folder.',
    'create_backup_success'   => 'Created backup successfully!',
    'delete_backup_success'   => 'Delete backup successfully!',
    'restore_backup_success'  => 'Restore backup successfully!',
    'generate_btn'            => 'Generate backup',
    'create'                  => 'Create backup',
    'restore'                 => 'Restore backup',
    'create_btn'              => 'Create',
    'restore_btn'             => 'Restore',
    'restore_confirm_msg'     => 'Do you really want to restore this revision?',
    'download_uploads_folder' => 'Download backup of uploads folder',
    'download_database'       => 'Download backup of database',
    'restore_tooltip'         => 'Restore this backup',
    'demo_alert'              => 'Hi guest, if you see demo site is destroyed, please help me <a href=":link">go here</a> and restore demo site to the latest revision! Thank you so much!',
    'menu_name'               => 'Backups',

    'settings' => [
        'title'                                 => 'Backup',
        'description'                           => 'Settings for backup function',
        'backup_mysql_execute_path'             => 'MySQL execute path',
        'backup_mysql_execute_path_placeholder' => 'Default: /usr/mysql/bin. Ignore it if you don\'t sure about it',
    ],
];
