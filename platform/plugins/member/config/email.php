<?php

return [
    'name'        => 'plugins/member::member.settings.email.title',
    'description' => 'plugins/member::member.settings.email.description',
    'templates'   => [
        'new-pending-post' => [
            'title'       => 'New pending post',
            'description' => 'Send email to admin when a new post created',
            'subject'     => 'New post is pending on {{ site_title }} by {{ post_author }}',
            'can_off'     => true,
        ],
    ],
    'variables'   => [
        'post_author' => 'Post Author',
        'post_name'   => 'Post Name',
        'post_url'    => 'Post URL',
    ],
];
