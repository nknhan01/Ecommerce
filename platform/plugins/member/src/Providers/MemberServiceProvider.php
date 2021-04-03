<?php

namespace Platform\Member\Providers;

use BaseHelper;
use Platform\Blog\Models\Post;
use EmailHandler;
use Illuminate\Routing\Events\RouteMatched;
use Platform\Base\Supports\Helper;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Member\Http\Middleware\RedirectIfMember;
use Platform\Member\Http\Middleware\RedirectIfNotMember;
use Platform\Member\Models\Member;
use Platform\Member\Models\MemberActivityLog;
use Platform\Member\Repositories\Caches\MemberActivityLogCacheDecorator;
use Platform\Member\Repositories\Caches\MemberCacheDecorator;
use Platform\Member\Repositories\Eloquent\MemberActivityLogRepository;
use Platform\Member\Repositories\Eloquent\MemberRepository;
use Platform\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Platform\Member\Repositories\Interfaces\MemberInterface;
use Event;
use Illuminate\Support\ServiceProvider;
use Throwable;

class MemberServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        config([
            'auth.guards.member'     => [
                'driver'   => 'session',
                'provider' => 'members',
            ],
            'auth.providers.members' => [
                'driver' => 'eloquent',
                'model'  => Member::class,
            ],
            'auth.passwords.members' => [
                'provider' => 'members',
                'table'    => 'member_password_resets',
                'expire'   => 60,
            ],
            'auth.guards.member-api' => [
                'driver'   => 'passport',
                'provider' => 'members',
            ],
        ]);

        $router = $this->app->make('router');

        $router->aliasMiddleware('member', RedirectIfNotMember::class);
        $router->aliasMiddleware('member.guest', RedirectIfMember::class);

        $this->app->bind(MemberInterface::class, function () {
            return new MemberCacheDecorator(new MemberRepository(new Member));
        });

        $this->app->bind(MemberActivityLogInterface::class, function () {
            return new MemberActivityLogCacheDecorator(new MemberActivityLogRepository(new MemberActivityLog));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/member')
            ->loadAndPublishConfigurations(['general', 'permissions', 'assets', 'email'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'api'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-core-member',
                'priority'    => 22,
                'parent_id'   => null,
                'name'        => 'plugins/member::member.menu_name',
                'icon'        => 'fa fa-users',
                'url'         => route('member.index'),
                'permissions' => ['member.index'],
            ]);
        });

        $this->app->booted(function () {
            EmailHandler::addTemplateSettings(MEMBER_MODULE_SCREEN_NAME, config('plugins.member.email'));
        });

        $this->app->register(EventServiceProvider::class);

        add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 20, 0);

        add_action(BASE_ACTION_INIT, function () {
            if (defined('GALLERY_MODULE_SCREEN_NAME') && request()->segment(1) == 'account') {
                \Gallery::removeModule(Post::class);
            }
        }, 12, 2);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 49, 1);
    }

    /**
     * @return bool
     */
    public function setInAdmin(): bool
    {
        return in_array(request()->segment(1), ['account', BaseHelper::getAdminPrefix()]);
    }

    /**
     * @param null|string $data
     * @return string
     * @throws Throwable
     */
    public function addSettings($data = null)
    {
        return $data . view('plugins/member::settings')->render();
    }
}
