let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/backup';
const source = './platform/plugins/backup';

mix
    .js(source + '/resources/assets/js/backup.js', dist + '/js')
    .sass(source + '/resources/assets/sass/backup.scss', dist + '/css')

    .copy(dist + '/js', source + '/public/js')
    .copy(dist + '/css', source + '/public/css');
