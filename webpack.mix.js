const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 3 })
   .extract();

mix.sass('resources/sass/app.scss', 'public/css')
   .options({
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
   })
   .alias({
      '@': 'resources/js',
   })
   .define({
      __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'false',
   })
   .version();
