### local development:
1. clone the main pagebuilder repository, inside project root, run the following:
` git clone https://github.com/husseinelhussein/Laravel-Pagebuilder.git ./custom_packages/hansschouten/laravel-pagebuilder`

1. clone the themes repository, inside the project root run the following:
`git clone git@gitlab.com:devvly-internal/laravel-pagebuilder-custom-themes.git ./resources/pagebuilder-themes`
  this folder `/resources/pagebuilder-themes` contains all the themes for the pagebuilder for both its front-end and back-end (homepage, pages, blog posts and the editor styling).
  and twoa folder contains the theme for the pagebuilder for 2A Marketplace, while twoa-pro contains the theme for Whault/2A pro
   
1. **Creating a new theme**: <br>
   when you want to creat a new theme, it should have the same directory structure as twoa and twoa-pro,
   also note that it should have a file called `Theme.php` which is responsible for getting this theme's blocks

1. **adding a static block:**<br>
   create the folder for the block code under `resources/pagebuilder-themes/THEME/blocks`,
   and inside it create 2 files:
    * config.php
    * view.html

1. **adding a dynamic block:**<br>
   create the following files inside the block's folder:
    * config.php
    * builder_view.blade.php (for the editor only, not required)
    * view.blade.php (for front-end)
    * model.php (for handling getting the block data)
    * controller.php (will be processed whenever a user access the block, a good place to gather any data for the block and pass it to the view)
    
1. **editing the editor css:**<br>
    each theme should have `resources/THEME/assets/sass`, which contains all the styling.
    in the theme folder, you should have a file called builder.scss in this path: `resources/pagebuilder-themes/THEME/assets/sass/builder.scss`, which is specific for styling the editor

1. **editing the front-end css:**
    edit the following file: `resources/pagebuilder-themes/THEME/assets/sass/styles.scss`
      