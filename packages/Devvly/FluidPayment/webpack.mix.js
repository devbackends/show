const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
console.log("in_production:", mix.inProduction());
if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
	var publicPath = 'publishable/assets';
	// var publicPath = "../../../public/vendor/devvly/fluidpayment/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/app.js");

if (mix.inProduction()) {
    mix.version();
}