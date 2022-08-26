require('./bootstrap');

import Vue from 'vue'
window.Vue = Vue;

import App from './App.vue'
import router from './router'
import { store } from './store/store'
import vuetify from './plugins/vuetify';
import axios from 'axios';
import Vuetify from 'vuetify';
// Draggable
import draggable from 'vuedraggable'

router.beforeEach((to, from, next) => {
    axios.get('/usuario/info').then(response => {
        store.state.auth.info_usuario = response.data;
        if (!response.data.estatus) {
            next({path: '/page-error'});
        }
    }).catch(error => {
        if (error.response.status == 401) {
            store.state.snackbars.textMessage = 'Error de autenticación, recargue la página por favor.';
            store.state.snackbars.colorMessage = 'error';
            store.state.snackbars.snackbarsMessage = true;
            store.state.auth.vmodel_sheetLogin = true;
        }
        if (error.response.status == 419) {
            store.state.snackbars.textMessage = 'Error interno del servidor, recargue la página por favor';
            store.state.snackbars.colorMessage = 'error';
            store.state.snackbars.snackbarsMessage = true;
        }
        if (error.response.status == 500) {
            store.state.snackbars.textMessage = 'Error interno del servidor, recargue la página por favor';
            store.state.snackbars.colorMessage = 'error';
            store.state.snackbars.snackbarsMessage = true;
        }
        axios.post('system/logs', {
            debug: 'Fronted',
            estatus: error.response.status,
            menssage: error.response.statusText,
            file: 'main.js'
        }).catch(error => {
        });
    });
    next();
});

router.afterEach((to, from) => {
});


Vue.component('programa-crud', require('./components/programa/ProgramaComponent.vue').default);
Vue.component('programa-list', require('./components/programa/ListProgramaComponent.vue').default);
Vue.component('programa-data-table', require('./components/programa/DataTableComponent.vue').default);
Vue.component('podcast-crud', require('./components/podcast/PodcastComponent.vue').default);
Vue.component('podcast-list', require('./components/podcast/ListPodcastComponent.vue').default);
Vue.component('podcast-data-table', require('./components/podcast/DataTableComponent.vue').default);
Vue.component('blog-crud', require('./components/blog/BlogComponent.vue').default);
Vue.component('blog-list', require('./components/blog/ListBlogComponent.vue').default);
Vue.component('blog-data-table', require('./components/blog/DataTableComponent.vue').default);
Vue.component('multimedia-crud', require('./components/multimedia/MultimediaComponent.vue').default);
Vue.component('multimedia-list', require('./components/multimedia/ListMultimediaComponent.vue').default);
Vue.component('multimedia-data-table', require('./components/multimedia/DataTableComponent.vue').default);
Vue.component('programacion', require('./components/programacion/ProgramacionComponent.vue').default);
Vue.component('widget-snackbars-message', require('./components/widget/WidgetSnackbarsMessage.vue').default);
Vue.component('widget-sheets-loading', require('./components/widget/WidgetSheetsLoading.vue').default);
Vue.component('widget-dialog-confirmacion', require('./components/widget/WidgetDialogEliminar.vue').default);
Vue.component('widget-alert-count', require('./components/widget/WidgetAlertCount.vue').default);
Vue.component('widget-galeria', require('./components/widget/WidgetGaleria.vue').default);


new Vue({
    Vuetify: new Vuetify(),
    router,
    vuetify,
    store,
    render: h => h(App),
}).$mount('#altavozRadio');
