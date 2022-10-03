const mix = require("laravel-mix");

if (mix == 'undefined') {
    const { mix } = require("laravel-mix");
}

require("laravel-mix-merge-manifest");

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {

    var publicPath = 'publishable/assets';
    // var publicPath = "../../../public/vendor/webkul/admin/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.sass(__dirname + "/src/Resources/assets/custom-scss/custom-app.scss", "css/custom-admin.css")

mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/admin.js")
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/admin.css")
    .options({
        processCssUrls: false
    });

if (!mix.inProduction()) {
    mix.sourceMaps();
}

if (mix.inProduction()) {
    mix.version();
}