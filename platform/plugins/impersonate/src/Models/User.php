<?php

namespace Platform\Impersonate\Models;

use Platform\ACL\Models\User as BaseUser;
use Lab404\Impersonate\Models\Impersonate;

class User extends BaseUser
{
    use Impersonate;
}
