const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
var publicPath;
console.log('prod:', mix.inProduction());
if (mix.inProduction()) {
    publicPath = 'publishable/assets';
} else {
	//publicPath = 'publishable/assets';
	publicPath = "../../../public/vendor/devvly/onboarding/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();


mix.sass(__dirname + "/src/Resources/assets/sass/onboarding.scss", "css/onboarding.css")
		.options({
			processCssUrls: false
		});
mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/app.js");

if (mix.inProduction()) {
	mix.version();
}