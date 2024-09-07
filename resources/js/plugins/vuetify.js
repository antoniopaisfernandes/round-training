import colors from '../colors'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const theme = {
  icons: {
    iconfont: 'mdi',
  },
  themes: {
    light: {
      ...colors,
    },
  },
}

const breakpoint = {
  thresholds: {
    xs: 640,
    sm: 768,
    md: 1024,
    lg: 1280,
  },
  scrollBarWidth: 0.1,
}

import { en, pt } from 'vuetify/locale'

export default createVuetify({
  breakpoint,
  theme,
  locale: {
    locale: 'en',
    fallback: 'pt',
    messages: { en, pt },
  },
  components,
  directives,
})
