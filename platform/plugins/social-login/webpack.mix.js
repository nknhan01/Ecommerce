let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/social-login';
const source = './platform/plugins/social-login';

mix
    .js(source + '/resources/assets/js/social-login.js', dist + '/js')
    .copy(dist + '/js', source + '/public/js');
