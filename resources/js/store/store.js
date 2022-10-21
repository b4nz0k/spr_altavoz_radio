import Vue from 'vue'
import Vuex from 'vuex'

// modules
import auth from './modules/auth';
import snackbars from './modules/snackbars';
import sheets from './modules/sheets';
import dialog from './modules/dialog';
import dataTable from './modules/dataTable';
import modCaregorias from './modules/categorias';
import modExcel from './modules/excel'
import modSelect from './modules/select'

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        auth,
        snackbars,
        sheets,
        dialog,
        dataTable,
        cat: modCaregorias,
        excel: modExcel,
        select: modSelect
    },
    actions: {

    }
})
