const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
var publicPath = "../../../public/themes/market/assets";
if (mix.inProduction()) {
    publicPath = 'publishable/assets';
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js([__dirname + "/src/Resources/assets/js/app.js"], "js/marketplace.js")
    .js([__dirname + "/src/Resources/assets/js/checkout.js"], "js/checkout.js")
    .js([__dirname + "/src/Resources/assets/js/main.js"], "js/main.js")
    .copyDirectory(__dirname + "/src/Resources/assets/images", publicPath + "/images")
    .sass(__dirname + "/src/Resources/assets/sass/app.scss", "css/marketplace.css")
    .sass(__dirname + "/src/Resources/assets/sass/mpVelocity.scss", "css/mpVelocity.css")
    .sass(__dirname + "/src/Resources/assets/sass/admin.scss", "css/marketplace-admin.css")
    .sass(__dirname + "/src/Resources/assets/scss/app.scss", "css/market.css")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
if (!mix.inProduction()) {
    mix.sourceMaps();
}