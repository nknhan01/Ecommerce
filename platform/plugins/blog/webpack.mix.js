let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/blog';
const source = './platform/plugins/blog';

mix
    .js(source + '/resources/assets/js/blog.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
