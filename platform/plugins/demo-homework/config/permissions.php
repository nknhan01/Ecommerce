<?php

return [
    [
        'name' => 'Demo homeworks',
        'flag' => 'demo-homework.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'demo-homework.create',
        'parent_flag' => 'demo-homework.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'demo-homework.edit',
        'parent_flag' => 'demo-homework.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'demo-homework.destroy',
        'parent_flag' => 'demo-homework.index',
    ],
];
