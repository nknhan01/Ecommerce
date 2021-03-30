<?php

namespace Botble\ACL\Listeners;

use Assets;
use Exception;
use Illuminate\Support\Facades\Auth;
use Botble\ACL\Models\User;
use Botble\ACL\Models\UserMeta;
use Illuminate\Auth\Events\Login;

class LoginListener
{

    /**
     * Handle the event.
     *
     * @param Login $event
     * @return void
     *
     * @throws Exception
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        if ($user instanceof User) {
            $locale = UserMeta::getMeta('site-locale', false, $user->getKey());

            if ($locale != false && array_key_exists($locale, Assets::getAdminLocales())) {
                app()->setLocale($locale);
                session()->put('site-locale', $locale);
            }

            cache()->forget(md5('cache-dashboard-menu-' . Auth::user()->getKey()));
        }
    }
}
