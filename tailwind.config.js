module.exports = {
  prefix: 'tw-',
  content: [
    './resources/**/*.html',
    './resources/**/*.js',
    './resources/**/*.php',
    './resources/**/*.vue',
    './node_modules/vuetify/**/*.vue',
    './node_modules/vuetify/**/*.js',
  ],
  theme: {
    extend: {
      width: {
        '1/8': '12.5%',
        '5/100': '5%',
        '10/100': '10%',
        '15/100': '15%',
        '20/100': '20%',
      }
    },
  },
  variants: {},
  plugins: [],
}
