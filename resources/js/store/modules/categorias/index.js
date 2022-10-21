import axios from "axios";
export const namespaced = true

const state = {
    datos: {},

    item: {},

    urlGet:'cat/list',
    urlSearch:'cat/search?page=',
    urlStore: 'cat/add',
    urlUpdate: 'cat/update',
    urlDelete: 'cat/delete/',
    dialogOpcion: false,
    dialogDel: false,
    tituloDialog: '',
    utimaRuta: ''


};
const getters = {
    getItem: state => {
        return state.item
    },
    getTablas: state => {
        return state.datos
    },
    getDialogOpcion: state => {
        return state.dialogOpcion
    },
    getDialogDel: state => {
        return state.dialogDel
    },
    getTitulo: state => {
        return state.tituloDialog
    },
    // contador
    getCount: state => {
        return state.datos.length
    }
}
const mutations = {
    setTablas(state, payload) {
        state.datos = payload
    },
    setItem(state, payload) {
        state.item = payload
    },
    setCambioPagina(state, payload) {
        state.datos.current_page = payload
    },
    setRutas(state, payload) {
        // Cambiando rutas para los distintos crud
        state.urlGet = payload.urlGet
        state.urlSearch = payload.urlSearch
        state.urlStore = payload.urlStore
        state.urlUpdate = payload.urlUpdate
        state.urlDelete = payload.urlDelete
        // console.log('nuevas rutas', state)
    },
    setdialogOpcion(state, payload) {
        state.dialogOpcion = payload
    },
    setdialogDel(state, payload) {
        state.dialogDel = payload
    },
    setTitulo(state, payload) {
        state.tituloDialog = payload
    },
    setUltimaRuta(state, payload) {
        state.utimaRuta = payload
    },
}
const actions = {
    async cargarTablas({commit, state}, numPagina) {
        try {
            await axios.get(state.urlGet+'?page='+numPagina)
                .then(response => {
                    commit('setTablas', response.data)
                    let ultimaRuta = state.urlGet+'?page='+numPagina
                    commit('setUltimaRuta', ultimaRuta)
                }).catch(error => {
                    dispatch('mensajeError', error)
                    })
        } catch (error) {
            console.log(error)
        };

    },
    async buscarTablas({commit, state, dispatch}, buscar) {
        try {
            if (buscar.palabra.length < 2) {
                dispatch('cargarTablas', 1)
            }
            else {
                await axios.post(state.urlSearch + buscar.pagina, { search: buscar.palabra})
                    .then(response => {
                        let cat = response.data
                            cat.buscar = buscar
                        commit('setTablas', cat)
                        // console.log(response.data);
                }).catch(error => {
                    dispatch("mensajeError", error.response)
                })
            }
        } catch (error) {
            console.log(error);
        }

    },
    // Update tablas
    async updateTabla({dispatch, state}, item) {
        try {
            // console.log('entrando al update');
            await axios.post(state.urlUpdate , item ).then(response => {
                console.log(response.data);
                if (response.data.answer) {
                    dispatch("mensajeExito", response.data)
                    dispatch("cargarTablas", state.ultimaRuta)
                }
                else
                    dispatch("mensajeError", error.response)
            })
        } catch (error) {
            console.log(error);
        };
    },
    async storeTabla({dispatch, state}, item) {
        try {
            // console.log('entrando al Store');
            await axios.post(state.urlStore , item ).then(response => {
                // console.log(response.data);
                if (response.data.answer) {
                    dispatch("mensajeExito", response.data)
                    dispatch("cargarTablas", state.ultimaRuta)
                }
                else
                    dispatch("mensajeError", error.response)
            })
        } catch (error) {
            console.log(error);
        };
    },
    async eliminarTabla({dispatch, state}, item) {
        try {
            await axios.get(state.urlDelete + item).then(response=>{
                // console.log(response.data);
                if (response.data.answer) {
                    dispatch("mensajeExito", response.data);
                    dispatch("cargarTablas", state.ultimaRuta)
                }
                else
                    dispatch("mensajeError", error.response)
            }).catch(error => {
                dispatch("mensajeError", error.response)
            })
        } catch (error) {
           console.log(error);
        };
    },
    async postTabla({dispatch, state}, datos) {
        try {
            // console.log('entrando ala funcion postTabla', datos.data)
            await axios.post(datos.url, datos.data).then(response => {
                console.log(response.data)
                if (response.data.answer) {
                    dispatch("mensajeExito", response.data);
                    dispatch("cargarTablas", state.ultimaRuta)
                }
                else {
                    dispatch("mensajeError", error.response)
                }
            }).catch(error => {
                dispatch("mensajeError", error.response)
            })
        } catch (error) {
           console.log(error);
        };
    },
    cargarItem({commit}, datos) {
        commit('setItem', datos)
    },
    cambiarDialogOpcion({commit}, value) {
        commit('setdialogOpcion', value)
    },
    cambiarDialogDel({commit}, value) {
        commit('setdialogDel', value)
    },
    cambiarTitulo({commit}, nombreTitulo) {
        commit('setTitulo', nombreTitulo)
    },
    cambiarRutas({commit}, rutas) {
        commit('setRutas', rutas)
    },

    mensajeExito({dispatch},mensaje) {
        // console.log('entrando a mensajeExito')
        dispatch('sanckbarsMessage', [mensaje.msg, 'success', true, '', ['top', 'right']], {root:true});
    },
    mensajeError({dispatch},error) {
        if (error.status == 401) {
            dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', []], {root:true});
        }
        if (error.status == 419) {
            dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []], {root:true});
        }
        if (error.status == 500) {
            dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []], {root:true});
        }
          axios.post('system/logs', {
                debug: 'Fronted',
                estatus: error.status,
                menssage: error.statusText,
                file: 'components\\programa\\DataTableComponent.vue'
            })
    }
}


export default {
    namespaced,
    state,
    getters,
    actions,
    mutations
}
