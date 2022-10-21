const state = {
    sheetCerKey: false,
    statusCerKey: false,

    sheetLoading: false,
    sheetLoadingText: '',
};

const getters = {
    getSheetCerKey: state => {
        return state.sheetCerKey;
    },

    getConfirmacionCerKey: state => {
        return state.statusCerKey;
    },

    getSheetLoading: state => {
        return state.sheetLoading;
    },

    getSheetLoadingText: state => {
        return state.sheetLoadingText;
    }
};

const actions = {
};

const mutations = {
    setSheetCerKey(state, sheet) {
        state.sheetCerKey = sheet;
    },

    setConfirmacionCerKey(state, status) {
        state.statusCerKey = status;
    },

    setSheetLoading(state, sheet) {
        state.sheetLoading = sheet;
    },

    setSheetLoadingText(state, info) {
        state.sheetLoadingText = info;
    },
};

export default {
    state,
    getters,
    actions,
    mutations
}