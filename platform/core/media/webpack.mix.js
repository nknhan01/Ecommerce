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

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'platform/core/' + directory;
const dist = 'public/vendor/core/core/' + directory;

mix
    .sass(source + '/resources/assets/sass/media.scss', dist + '/css')
    .js(source + '/resources/assets/js/media.js', dist + '/js')
    .js(source + '/resources/assets/js/jquery.addMedia.js', dist + '/js')
    .js(source + '/resources/assets/js/integrate.js', dist + '/js')

    .copyDirectory(dist + '/js', source + '/public/js')
    .copyDirectory(dist + '/css', source + '/public/css')
