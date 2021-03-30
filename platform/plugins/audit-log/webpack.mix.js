let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/audit-log';
const source = './platform/plugins/audit-log';

mix
    .js(source + '/resources/assets/js/audit-log.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
