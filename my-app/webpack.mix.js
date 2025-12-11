const mix = require('laravel-mix');
const path = require('path');

mix.copy('resources/views/index.html', 'public/');
mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sourceMaps()
   .options({
     processCssUrls: false,
     postCss: [
       require('autoprefixer')
     ]
   });
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('resources/js')
    }
  }
});