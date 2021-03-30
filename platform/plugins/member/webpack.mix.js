let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/member';
const source = './platform/plugins/member';

mix
    .js(source + '/resources/assets/js/member-admin.js', dist + '/js')
    .js(source + '/resources/assets/js/app.js', dist + '/js')

    .sass(source + '/resources/assets/sass/member.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/app.scss', dist + '/css')

    .copy(dist + '/js', source + '/public/js')
    .copy(dist + '/css', source + '/public/css');
