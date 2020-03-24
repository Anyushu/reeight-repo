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

// 共通
mix.js([
  'resources/js/app.js',
], 'public/js').sourceMaps().version();

// SEOレポート時
mix.js([
  'resources/js/ajax.js',
  'resources/js/ajax-stop.js',
], 'public/js/seo.js').sourceMaps().version();

// GAレポート時
mix.js([
  'resources/js/app-report.js',
], 'public/js/report.js').sourceMaps().version();

mix.sass('resources/sass/app.scss', 'public/css').sourceMaps();