let mix = require('laravel-mix');

const source = 'platform/plugins/cookie-consent';
const dist = 'public/vendor/core/plugins/cookie-consent';

mix
    .sass(source + '/resources/assets/sass/cookie-consent.scss', dist + '/css')
    .copy(dist + '/css', source + '/public/css');
