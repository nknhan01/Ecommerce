<?php

return [
    'name'        => 'plugins/contact::contact.settings.email.title',
    'description' => 'plugins/contact::contact.settings.email.description',
    'templates'   => [
        'notice' => [
            'title'       => 'plugins/contact::contact.settings.email.templates.notice_title',
            'description' => 'plugins/contact::contact.settings.email.templates.notice_description',
            'subject'     => 'New contact from your site',
            'can_off'     => true,
        ],
    ],
];