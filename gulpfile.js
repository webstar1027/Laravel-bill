const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //The Assets Directory
    var Assets = 'resources/assets/',

    //The SASS Directory
    SASS = 'resources/assets/sass/';

    //Copy Fonts
    mix.copy( SASS + 'fonts/', 'public/fonts/' );
    
    //Copy Icons
    mix.copy( Assets + 'icons/', 'public/css/icons/' );

    //Copy Images
    mix.copy( Assets + 'images/', 'public/assets/images/' );

    //Copy Javascript
    mix.copy( Assets + 'js/', 'public/js/' );

    mix
        .sass('app.scss')
//        .webpack('main.js')
//        .webpack('admin.js')


});
