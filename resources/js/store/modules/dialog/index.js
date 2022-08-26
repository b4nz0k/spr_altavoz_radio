const state = {
    dialogConfirDelete: false,
    dialogConfirTitulo: '',
    dialogConfirMensaje: '',
    dialogConfirStatus: false,
    dialogGaleriaPath: null,
};

const getters = {
    getDialogConfirTitulo: state => {
        return state.dialogConfirTitulo;
    },
    getDialogConfirMensaje: state => {
        return state.dialogConfirMensaje;
    },
    getDialogConfirDelete: state => {
        return state.dialogConfirDelete;
    },
    getDialogConfirStatus: state => {
        return state.dialogConfirStatus;
    },

    getDialogGaleriaPath: state => {
        return state.dialogGaleriaPath;
    },
};

const actions = {
    dialogConfirmacionDelete(context, info) {
        context.commit('setDialogConfirmDelete', info);
    }
};

const mutations = {
    setDialogConfirmDelete(state, info) {
        state.dialogConfirTitulo =      info[0];
        state.dialogConfirMensaje =     info[1];
        state.dialogConfirDelete =      info[2];
    },

    setDialogConfirStatus(state, status) {
        state.dialogConfirStatus = status;
    },

    hiddenDialogConfirmDelete(state, hidden) {
        state.dialogConfirDelete = hidden;
    },

    setDialogGaleriaPath(state, path) {
        state.dialogGaleriaPath = path;
    },
};

export default {
    state,
    getters,
    actions,
    mutations
}