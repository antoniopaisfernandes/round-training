const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const rootPath = Mix.paths.root.bind(Mix.paths);
require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 2 })
   .extract();

mix.sass('resources/sass/app.scss', 'public/css')
   .purgeCss({
      content: [
         rootPath('app/**/*.php'),
         rootPath('resources/**/*.html'),
         rootPath('resources/**/*.js'),
         rootPath('resources/**/*.php'),
         rootPath('resources/**/*.vue'),
         rootPath('node_modules/vuetify/**/*.vue'),
         rootPath('node_modules/vuetify/**/*.js'),
      ],
      whitelistPatterns: [/^theme/, /^mdi/, /^v-/, /-active$/, /-enter$/, /-leave-to$/]
   });

mix.options({
      // Remove LICENCE files from builds
      terser: {
         terserOptions: {
            output: {
               comments: false,
            },
         },
         extractComments: false,
      },
      // TailwindCSS
      processCssUrls: false,
      postCss: [tailwindcss('./tailwind.config.js')],
   });

if (mix.inProduction()) {
   mix.version();
} else {
   mix.browserSync('localhost:8000');
}
