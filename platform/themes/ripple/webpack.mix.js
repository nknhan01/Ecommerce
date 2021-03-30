let mix = require('laravel-mix');

const source = 'platform/themes/ripple';
const dist = 'public/themes/ripple';

mix
    .sass(source + '/assets/sass/style.scss', dist + '/css')
    .copy(dist + '/css/style.css', source + '/public/css')
    .js(source + '/assets/js/ripple.js', dist + '/js')
    .copy(dist + '/js/ripple.js', source + '/public/js');
