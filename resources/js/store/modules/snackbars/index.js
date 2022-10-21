

const state = {
    colorMessage: '',
    snackbarMessage: false,
    textMessage: '',
    left: false,
    right: false,
    top: false,
    bottom: false,
    timeout: 6000,
};

const getters = {
    getSnackbarTextMessage: state => {
        return state.textMessage;
    },

    getSnackbarColorMessage: state => {
        return state.colorMessage;
    },

    getSnackbarMessage: state => {
        return state.snackbarMessage;
    },

    getTimeout: state => {
        return state.timeout;
    },

    getLeft: state=> {
        return state.left;
    },

    getRight: state => {
        return state.right;
    },

    getTop: state => {
        return state.top;
    },

    getBottom: state => {
        return state.bottom;
    },
};

const actions = {
    sanckbarsMessage(context, info) {
        context.commit('setSnackbarMessage', info);
    },
    mensajeNotificacion(mensaje) {
        // return console.log('dentro del modulo')
        // commit('setEnviarMensaje', msj)
        $trueFalso = mensaje.data.answer
        $mensajeRes = mensaje.data.msg
        $color = (mensaje.data.answer == true) ? 'success' : 'error'
        this.$store.dispatch('sanckbarsMessage', [$mensajeRes, $color, $trueFalso, '', ['top', 'right']]);
        // sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
    },
};

const mutations = {
    setSnackbarMessage(state, info) {
        state.textMessage      = info[0];
        state.colorMessage     = info[1];
        state.snackbarMessage  = info[2];
        state.timeout          = (info[3]) ? info[3] : 6000;
        if (info[4].length > 0) {
            info[4].forEach(element => {
                switch (element) {
                    case 'left':
                        state.left = true;
                        break;
                    case 'right':
                        state.right = true;
                        break;
                    case 'top':
                        state.top = true;
                        break;
                    case 'bottom':
                        state.bottom = true;
                        break;
                }
            });
        } else {
            state.top = true;
        }
    },

    hideSnackbarMessage(state, hidden) {
        state.snackbarMessage = hidden;
    },

    setTimeout(state, time) {
        state.timeout = time;
    },

    setLeft(state, boolean) {
        state.left = boolean;
    },

    setRight(state, boolean) {
        state.right = boolean;
    },

    setTop(state, boolean) {
        state.top = boolean;
    },

    setBottom(state, boolean) {
        state.bottom = boolean;
    },
};

export default {
//   namespaced: true,
    state,
    getters,
    actions,
    mutations
}
