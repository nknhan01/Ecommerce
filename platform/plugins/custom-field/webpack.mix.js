let mix = require('laravel-mix');

const dist = 'public/vendor/core/plugins/custom-field';
const source = './platform/plugins/custom-field';

mix
    .sass(source + '/resources/assets/sass/edit-field-group.scss', dist + '/css')
    .sass(source + '/resources/assets/sass/custom-field.scss', dist + '/css')
    .js(source + '/resources/assets/js/edit-field-group.js', dist + '/js')
    .js(source + '/resources/assets/js/use-custom-fields.js', dist + '/js')
    .js(source + '/resources/assets/js/import-field-group.js', dist + '/js')

    .copy(dist + '/css', source + '/public/css')
    .copy(dist + '/js', source + '/public/js');
