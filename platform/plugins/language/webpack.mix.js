let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/language';
const source = './platform/plugins/language';

mix
    .js(source + '/resources/assets/js/language.js', dist + '/js/language.js')
    .js(source + '/resources/assets/js/language-global.js', dist + '/js/language-global.js')
    .js(source + '/resources/assets/js/language-public.js', dist + '/js')
    .js(source + '/resources/assets/js/theme-translations.js', dist + '/js')

    .sass(source + '/resources/assets/sass/language.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/language-public.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/theme-translations.scss', dist + '/css')

    .copy(dist + '/js', source + '/public/js')
    .copy(dist + '/css', source + '/public/css');
