let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.options({
    processCssUrls: false
});

const source = 'platform/core';
const dist = 'public/vendor/core';

let glob = require('glob');

glob.sync(source + '/base/resources/assets/sass/base/themes/*.scss').forEach(item => {
    if (item.indexOf('_base.scss') !== -1) {
        return;
    }

    mix.sass(item, dist + '/css/themes').copy(dist + '/css/themes', source + '/base/public/css/themes');
})

mix
    .js(source + '/acl/resources/assets/js/profile.js', dist + '/js')
    .copy(dist + '/js/profile.js', source + '/acl/public/js')
    .js(source + '/acl/resources/assets/js/login.js', dist + '/js')
    .copy(dist + '/js/login.js', source + '/acl/public/js')
    .js(source + '/acl/resources/assets/js/role.js', dist + '/js')
    .copy(dist + '/js/role.js', source + '/acl/public/js')
    .sass(source + '/acl/resources/assets/sass/login.scss', dist + '/css')
    .copy(dist + '/css/login.css', source + '/acl/public/css')

    .sass(source + '/base/resources/assets/sass/core.scss', dist + '/css')
    .copy(dist + '/css/core.css', source + '/base/public/css')
    .sass(source + '/base/resources/assets/sass/custom/system-info.scss', dist + '/css')
    .copy(dist + '/css/system-info.css', source + '/base/public/css')
    .sass(source + '/base/resources/assets/sass/custom/email.scss', dist + '/css')
    .copy(dist + '/css/email.css', source + '/base/public/css')
    .js(source + '/base/resources/assets/js/app.js', dist + '/js')
    .copy(dist + '/js/app.js', source + '/base/public/js')
    .js(source + '/base/resources/assets/js/core.js', dist + '/js')
    .copy(dist + '/js/core.js', source + '/base/public/js')
    .js(source + '/base/resources/assets/js/editor.js', dist + '/js')
    .copy(dist + '/js/editor.js', source + '/base/public/js')
    .js(source + '/base/resources/assets/js/cache.js', dist + '/js')
    .copy(dist + '/js/cache.js', source + '/base/public/js')
    .js(source + '/base/resources/assets/js/tags.js', dist + '/js')
    .copy(dist + '/js/tags.js', source + '/base/public/js')
    .js(source + '/base/resources/assets/js/system-info.js', dist + '/js')
    .copy(dist + '/js/system-info.js', source + '/base/public/js')

    .js(source + '/dashboard/resources/assets/js/dashboard.js', dist + '/js')
    .copy(dist + '/js/dashboard.js', source + '/dashboard/public/js')
    .sass(source + '/dashboard/resources/assets/sass/dashboard.scss', dist + '/css')
    .copy(dist + '/css/dashboard.css', source + '/dashboard/public/css')

    .sass(source + '/media/resources/assets/sass/media.scss', dist + '/media/css')
    .js(source + '/media/resources/assets/js/media.js', dist + '/media/js')
    .js(source + '/media/resources/assets/js/jquery.addMedia.js', dist + '/media/js')
    .js(source + '/media/resources/assets/js/integrate.js', dist + '/media/js')
    .copy(dist + '/media/js', source + '/media/public/media/js')
    .copy(dist + '/media/css', source + '/media/public/media/css')

    .js(source + '/setting/resources/assets/js/setting.js', dist + '/js')
    .copy(dist + '/js/setting.js', source + '/setting/public/js')
    .sass(source + '/setting/resources/assets/sass/setting.scss', dist + '/css')
    .copy(dist + '/css/setting.css', source + '/setting/public/css')

    .js(source + '/table/resources/assets/js/table.js', dist + '/js')
    .copy(dist + '/js/table.js', source + '/table/public/js')
    .js(source + '/table/resources/assets/js/filter.js', dist + '/js')
    .copy(dist + '/js/filter.js', source + '/table/public/js')
    .sass(source + '/table/resources/assets/sass/table.scss', dist + '/css')
    .copy(dist + '/css/table.css', source + '/table/public/css');

