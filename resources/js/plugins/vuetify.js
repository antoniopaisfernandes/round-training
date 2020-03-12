import Vue from 'vue';
import Vuetify from 'vuetify';
// import Vuetify from 'vuetify/lib';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/dist/vuetify.min.css';

Vue.use(Vuetify);

const theme = {
    icons: {
        iconfont: 'mdi'
    },
    // themes: {
    //     light: {
    //         ...appColors
    //     }
    // }
};

const breakpoint = {
    thresholds: {
        xs: 640,
        sm: 768,
        md: 1024,
        lg: 1280,
    },
    // We mark scrollBarWidth as 0.1 (0 defaults to 16) because Vuetify deducts it from
    // breakpoints (and we want it to match Tailwind)
    scrollBarWidth: 0.1
};

import pt from 'vuetify/src/locale/pt.ts';
import en from 'vuetify/src/locale/en.ts';

export default new Vuetify({
    breakpoint,
    theme,
    lang: {
        locales: { pt, en },
        current: 'pt',
    },
});
