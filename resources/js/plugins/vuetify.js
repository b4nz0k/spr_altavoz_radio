import Vue from 'vue';
import Vuetify from 'vuetify';
import es from 'vuetify/es5/locale/es';


Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        options: {
            customProperties: true,
        },
        themes: {
            light: {
                primary: '#27354D',
                secondary: '#424242',
                accent: '#82B1FF',
                error: '#FF5252',
                info: '#2196F3',
                success: '#4CAF50',
                warning: '#FFC107'
            },
        },
    },
    icons: {
        iconfont: 'md',
    },lang: {
        locales: { es },
        current: 'es',
    },
});
