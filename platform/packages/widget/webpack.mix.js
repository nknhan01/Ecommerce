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

const source = 'platform/packages/widget';
const dist = 'public/vendor/core/packages/widget';

mix
    .js(source + '/resources/assets/js/widget.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
