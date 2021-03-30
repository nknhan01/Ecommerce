let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/contact';
const source = './platform/plugins/contact';

mix
    .sass(source + '/resources/assets/sass/contact.scss', dist + '/css')
    .js(source + '/resources/assets/js/contact.js', dist + '/js')

    .copy(dist + '/css', source + '/public/css')
    .copy(dist + '/js', source + '/public/js');
