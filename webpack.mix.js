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

 mix.copy('node_modules/chart.js/dist', 'public/lib/chart.js');
 mix.copy('node_modules/jquery/dist', 'public/lib/jquery');
 mix.copy('node_modules/summernote/dist', 'public/lib/summernote');
 mix.copy('node_modules/sweetalert2/dist', 'public/lib/sweetalert2');
 mix.copy('node_modules/@fortawesome/fontawesome-free', 'public/lib/fontawesome-free');
 mix.copy('node_modules/country-state-city/lib', 'public/lib/country-state-city');

 mix.js('resources/js/app.js', 'public/js')
     .postCss('resources/css/app.css', 'public/css');
