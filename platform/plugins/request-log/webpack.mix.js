let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/request-log';
const source = './platform/plugins/request-log';

mix
    .js(source + '/resources/assets/js/request-log.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
