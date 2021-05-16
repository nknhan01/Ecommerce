<?php

namespace Platform\ACL\Http\Controllers\Auth;

use Assets;
use Platform\Base\Http\Controllers\BaseController;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Contracts\View\Factory;
use Platform\ACL\Traits\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\View\View;

class createAccountSaller extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Create Account Seller Controller
    |--------------------------------------------------------------------------
    */
    public function showLinkCreateForm()
    {
        page_title()->setTitle('Create Account For Seller');

        Assets::addScripts(['jquery-validation'])
            ->addScriptsDirectly('vendor/core/core/acl/js/login.js')
            ->addStylesDirectly('vendor/core/core/acl/css/login.css')
            ->removeStyles([
                'select2',
                'fancybox',
                'spectrum',
                'simple-line-icons',
                'custom-scrollbar',
                'datepicker',
            ])
            ->removeScripts([
                'select2',
                'fancybox',
                'cookie',
            ]);
        return view('core/acl::auth.create-account-seller');
    }

}
