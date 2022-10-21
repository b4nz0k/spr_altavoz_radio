const state = {
    info_usuario: [],
    loading: false,
    dialog: false,
    vmensaje: '',
    unauthorized: false,
    vmodel_sheetLogin: false,
};

const getters = {
    infoUsuario: state => {
        return state.info_usuario;
    },

    getLoading: state => {
        return state.loading;
    },

    getDialog: state => {
        return state.dialog;
    },

    getDialogMensaje: state => {
        return state.vmensaje;
    },

    getUnauthorized: state => {
        return state.unauthorized;
    },

    getVmodel_sheetLogin: state => {
        return state.vmodel_sheet;
    },
};

const actions = {
    logInUser (context, loginForm) {
        if (loginForm.username != '' && loginForm.password != '') {
            axios.post("ldap", {
                username: loginForm.username + '@sistema.spr.gob.mx',
                password: loginForm.password
            }).then(response => {
                
                if (response.data.answer) {
                    let formLogin = new FormData();

                    formLogin.append("email",  loginForm.username + '@sistema.spr.gob.mx');
                    formLogin.append("password", loginForm.password);
                    axios.post("login", formLogin).then(response => {
                        console.log(response.data);
                        if (loginForm.sheet) {
                            context.commit('setUnauthorized', true);
                            context.commit('setVmodel_sheetLogin', false);
                        } else {
                            window.location = "/";
                        }
                    }).catch(error => {
                        if (error.response.status == 401) {
                            this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true]);
                        }
                        if (error.response.status == 419) {
                            this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true]);
                        }
                        if (error.response.status == 500) {
                            this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true]);
                        }
                        axios.post('system/logs', {
                            debug: 'Fronted',
                            estatus: error.response.status,
                            menssage: error.response.statusText,
                            file: 'index.js'
                        }).catch(error => {
                        });
                    });
                } else {
                    context.commit('vDialogMensaje', 'Se ha producido un error, verifica que tus claves sean las correctas..');
                    context.commit('vdialog', true);
                }
            })
            .catch(error => {
                console.log(error.response);
                context.commit('vDialogMensaje', 'Se ha producido un error, intente de nuevo recargar el sistema o elimine la caché de su navegador.');
                context.commit('vdialog', true);
            });
        } else {
            
        }
    },

    logInUserInfo (context) {
        axios.get('usuario/info').then(response => {
            context.commit('loginSuccess', response.data); 
        });
    },

    singOff (context) {
        axios.post("logout",{
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        }).then(function(response) {
            context.commit('singOffSuccess');
            window.location.reload();
        }).catch(error => {
            if (error.response.status == 401) {
                this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true]);
            }
            if (error.response.status == 419) {
                this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true]);
            }
            if (error.response.status == 500) {
                this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true]);
            }
            axios.post('system/logs', {
                debug: 'Fronted',
                estatus: error.response.status,
                menssage: error.response.statusText,
                file: 'index.js'
            }).catch(error => {
                console.log(error.response);
            });
        });
    }
};

const mutations = {
    loginSuccess (state, usuario) {
        state.info_usuario = usuario;
    },

    singOffSuccess (state, usuario) {
    },

    booleanProgress (state, boolean) {
        state.loading = boolean;
    },

    vdialog (state, boolean) {
        state.dialog = boolean;
    },

    vDialogMensaje (state, msg) {
        state.vmensaje = msg;
    },

    setUnauthorized(state, estatus) {
        state.unauthorized = estatus;
    },

    setVmodel_sheetLogin(state, estatus) {
        state.vmodel_sheet = estatus;
    },

};

export default {
    state,
    getters,
    actions,
    mutations
}