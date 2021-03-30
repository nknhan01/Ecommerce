let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const source = 'platform/packages/js-validation';
const dist = 'public/vendor/core/packages/js-validation';

mix
    .scripts(
        [
            source + '/resources/assets/js/jquery-validation/jquery.validate.js',
            source + '/resources/assets/js/phpjs/strlen.js',
            source + '/resources/assets/js/phpjs/array_diff.js',
            source + '/resources/assets/js/phpjs/strtotime.js',
            source + '/resources/assets/js/phpjs/is_numeric.js',
            source + '/resources/assets/js/php-date-formatter/php-date-formatter.js',
            source + '/resources/assets/js/js-validation.js',
            source + '/resources/assets/js/helpers.js',
            source + '/resources/assets/js/timezones.js',
            source + '/resources/assets/js/validations.js'
        ], dist + '/js/js-validation.js')
    .copy(dist + '/js', source + '/public/js');
