let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/translation';
const source = './platform/plugins/translation';

mix
    .js(source + '/resources/assets/js/translation.js', dist + '/js')
    .sass(source + '/resources/assets/sass/translation.scss', dist + '/css')

    .copy(dist + '/js', source + '/public/js')
    .copy(dist + '/css', source + '/public/css');
