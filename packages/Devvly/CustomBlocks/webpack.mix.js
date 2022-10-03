const mix = require('laravel-mix');
if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = 'publishable/assets';
    var publicPath = "../../../public/vendor/devvly/customblocks/assets";
}
mix.setPublicPath(publicPath);
mix.disableNotifications();
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
mix.sass(__dirname + "/src/Resources/assets/sass/app.scss", "css")
    .options({
        processCssUrls: false
    });
mix.react(__dirname + '/src/resources/assets/js/app.js', 'js');