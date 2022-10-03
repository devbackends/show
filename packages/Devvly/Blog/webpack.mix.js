const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
var publicPath = 'publishable/assets';
if (!mix.inProduction()) {
    //publicPath = "../../../public/vendor/devvly/blog/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();


mix.sass(__dirname + "/src/Resources/assets/sass/app.scss", "css")
    .options({
        processCssUrls: false
    });
mix.js(__dirname + "/src/Resources/assets/js/app.js", "js");

if (mix.inProduction()) {
    mix.version();
}