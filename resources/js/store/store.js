import Vue from 'vue'
import Vuex from 'vuex'

// modules
import auth from './modules/auth';
import snackbars from './modules/snackbars';
import sheets from './modules/sheets';
import dialog from './modules/dialog';
import dataTable from './modules/dataTable';

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        auth,
        snackbars,
        sheets,
        dialog,
        dataTable
    }
})