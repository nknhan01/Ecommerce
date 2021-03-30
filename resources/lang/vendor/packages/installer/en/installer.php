<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title'        => 'Installer',
    'next'         => 'Next Step',
    'back'         => 'Previous',
    'finish'       => 'Install',
    'forms'        => [
        'errorTitle' => 'The following errors occurred:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome'      => [
        'templateTitle' => 'Botble CMS',
        'title'         => 'Botble CMS',
        'message'       => 'Welcome to Botble CMS. Before getting started, we need some information on the database. You will need to know the following items before proceeding.',
        'next'          => 'Let\'s go',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Step 1 | Server Requirements',
        'title'         => 'Server Requirements',
        'next'          => 'Check Permissions',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions'  => [
        'templateTitle' => 'Step 2 | Permissions',
        'title'         => 'Permissions',
        'next'          => 'Configure Environment',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment'  => [
        'wizard'  => [
            'templateTitle' => 'Environment Settings',
            'title'         => 'Environment Settings',
            'form'          => [
                'name_required'              => 'An environment name is required.',
                'app_name_label'             => 'Site title',
                'app_name_placeholder'       => 'Site title',
                'app_url_label'              => 'URL',
                'app_url_placeholder'        => 'URL',
                'db_connection_label'        => 'Database Connection',
                'db_connection_label_mysql'  => 'MySQL',
                'db_connection_label_sqlite' => 'SQLite',
                'db_connection_label_pgsql'  => 'PostgreSQL',
                'db_host_label'              => 'Database host',
                'db_host_placeholder'        => 'Database host',
                'db_port_label'              => 'Database port',
                'db_port_placeholder'        => 'Database port',
                'db_name_label'              => 'Database name',
                'db_name_placeholder'        => 'Database name',
                'db_username_label'          => 'Database username',
                'db_username_placeholder'    => 'Database username',
                'db_password_label'          => 'Database password',
                'db_password_placeholder'    => 'Database password',
                'buttons'                    => [
                    'install' => 'Install',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Step 3 | Environment Settings | Classic Editor',
            'title'         => 'Classic Environment Editor',
            'save'          => 'Save .env',
            'back'          => 'Use Form Wizard',
            'install'       => 'Save and Install',
        ],
        'success' => 'Your .env file settings have been saved.',
        'errors'  => 'Unable to save the .env file, Please create it manually.',
    ],

    'install' => 'Install',

    /**
     *
     * Final page translations.
     *
     */
    'final'   => [
        'title'         => 'Installation Finished',
        'templateTitle' => 'Installation Finished',
        'finished'      => 'Application has been successfully installed.',
        'migration'     => 'Migration & Seed Console Output:',
        'console'       => 'Application Console Output:',
        'log'           => 'Installation Log Entry:',
        'env'           => 'Final .env File:',
        'exit'          => 'Click here to exit',
    ],
];
