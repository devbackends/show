const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");

var publicPath = "../../../public/themes/velocity/assets";

if (mix.inProduction()) {
    publicPath = 'publishable/assets';
}

console.log(publicPath);

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix
    .js(
        __dirname + "/src/Resources/assets/js/app.js",
        __dirname + '/' + publicPath + "/js/velocity.js"
    )
    .js(
        __dirname + "/src/Resources/assets/js/checkout.js",
        __dirname + '/' + publicPath + "/js/checkout.js"
    )
    .sass(
        __dirname + '/src/Resources/assets/sass/admin.scss',
        __dirname + '/' + publicPath + '/css/velocity-admin.css'
    )
    .sass(
        __dirname + '/src/Resources/assets/sass/app.scss',
        __dirname + '/' + publicPath + '/css/velocity.css', {
            includePaths: ['node_modules/bootstrap/scss/'],
        }
    )
    .sass(
        __dirname + '/src/Resources/assets/custom-scss/custom-app.scss',
        __dirname + '/' + publicPath + '/css/custom-velocity.css'
    )

    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
if (!mix.inProduction()) {
    mix.sourceMaps();
}