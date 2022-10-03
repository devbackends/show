const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
var publicPath;
console.log('prod:', mix.inProduction());
if (mix.inProduction()) {
    publicPath = 'publishable/assets';
} else {
    publicPath = "../../../public/vendor/devvly/ffl/assets";
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.sass(__dirname + "/src/Resources/assets/sass/fflonboarding.scss", "css/fflonboarding.css")
		.options({
			processCssUrls: false
		}).sass(__dirname + "/src/Resources/assets/sass/admin.scss", "css/admin.css");

mix.js(__dirname + "/src/Resources/assets/js/app.js", "js/app.js");
mix.js(__dirname + "/src/Resources/assets/js/admin.js", "js/admin.js");

if (mix.inProduction()) {
	mix.version();
}
