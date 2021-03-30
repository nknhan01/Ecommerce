let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/analytics';
const source = './platform/plugins/analytics';

mix
    .js(source + '/resources/assets/js/analytics.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
