import { createVuetify } from 'vuetify'
import colors from '../colors'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { pt, en } from 'vuetify/locale'

const vuetify = createVuetify({
  display: {
    mobileBreakpoint: 'sm',
    thresholds: {
      xs: 0,
      sm: 640,
      md: 768,
      lg: 1024,
      xl: 1280,
    },
  },
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          ...colors
        }
      }
    }
  },
  locale: {
    locale: 'pt',
    messages: { pt, en }
  }
})

export default vuetify
