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

const source = 'platform/packages/plugin-management';
const dist = 'public/vendor/core/packages/plugin-management';

mix
    .js(source + '/resources/assets/js/plugin.js', dist + '/js')
    .copy(dist + '/js/plugin.js', source + '/public/js')
    .sass(source + '/resources/assets/sass/plugin.scss', dist + '/css')
    .copy(dist + '/css/plugin.css', source + '/public/css');
