const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
console.log("in_production:", mix.inProduction());
if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = "../../../public/vendor/devvly/subscription/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();


mix.sass(__dirname + "/src/Resources/assets/sass/subscription.scss", "css/subscription.css")
    .options({
        processCssUrls: false
    });
mix.js(__dirname + "/src/Resources/assets/js/devvly_clearent.js", "js/devvly_clearent.js");

if (mix.inProduction()) {
    mix.version();
}