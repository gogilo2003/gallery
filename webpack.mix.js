const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/gallery.js', 'public/js')
    .js('resources/assets/js/front.js', 'public/js')
    .vue()
    .sass('resources/assets/scss/gallery.scss', 'public/css')
    .sass('resources/assets/scss/front.scss', 'public/css');

// .postCss('resources/assets/css/gallery.css', 'public/css', [
//     //
// ]);