const mix = require('laravel-mix');

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

 mix.copy('node_modules/chart.js', 'public/lib/chart.js');
 mix.copy('node_modules/jquery', 'public/lib/jquery');
 mix.copy('node_modules/summernote', 'public/lib/summernote');
 mix.copy('node_modules/sweetalert2', 'public/lib/sweetalert2');
 mix.copy('node_modules/bootstrap', 'public/lib/bootstrap');
 mix.copy('node_modules/@fortawesome/fontawesome-free', 'public/lib/fontawesome-free');

 mix.js('resources/js/app.js', 'public/js')
     .postCss('resources/css/app.css', 'public/css');
