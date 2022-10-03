const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
console.log("in_production:", mix.inProduction());
if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
	var publicPath = 'publishable/assets';
	// var publicPath = "../../../public/vendor/devvly/clearentpayment/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();


mix.sass(__dirname + "/src/Resources/assets/sass/app.scss", "css")
    .options({
        processCssUrls: false
    });
mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/app.js");
mix.js(__dirname + "/src/Resources/assets/js/modal.js", "js/modal.js");
mix.js(__dirname + "/src/Resources/assets/js/toggle_card.js", "js/toggle_card.js");
mix.js(__dirname + "/src/Resources/assets/js/devvly_clearent.js", "js/devvly_clearent.js");

if (mix.inProduction()) {
    mix.version();
}