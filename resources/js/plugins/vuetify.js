import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import { pt, en } from 'vuetify/locale'
import colors from '../colors'

// Vuetify 3 theme configuration
const customTheme = {
  dark: false,
  colors: {
    ...colors
  }
}

// Vuetify 3 display breakpoints (aligned with Tailwind)
const display = {
  thresholds: {
    xs: 0,
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
  },
}

export default createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
  theme: {
    defaultTheme: 'customTheme',
    themes: {
      customTheme,
    },
  },
  display,
  locale: {
    locale: 'pt',
    fallback: 'en',
    messages: { pt, en },
  },
})
