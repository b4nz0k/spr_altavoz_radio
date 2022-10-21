import axios from "axios";
export const namespaced = true

const state = {
    items: { a:'asd'},
    pagina: 1,
    utimaRuta: null
}
const getters = {
    getItems: state => {
        return state.items
    },
}
const mutations = {
    setTablas(state, payload) {
        state.items = payload
        // console.log('mutacion state.items', state.items)
    },
    setUltimaRuta(state, payload) {
        state.utimaRuta = payload
    }
}
const actions = {
    async getTablas({commit, dispatch, state}, urlGet) {
        try {
            // return
            await axios.get(urlGet)
                .then(response => {
                        commit('setTablas', response.data)
                        // console.log('despues del commit', state.items)
                        }).catch(error => {
                        dispatch('mensajeError', error)
                    })
                } catch (error) {
                    console.log(error);
                };
            commit('setUltimaRuta', urlGet)
    },
    async postTablas({commit, dispatch, state}, urlGet) {
        try {
            // return
            await axios.post(urlGet)
                .then(response => {
                        commit('setTablas', response.data)
                        // console.log('despues del commit', state.items)
                        }).catch(error => {
                        dispatch('mensajeError', error)
                    })
                } catch (error) {
                    console.log(error);
                };
    },
    async buscarTablas({commit, state, dispatch}, buscar) {
        try {
            if (buscar.palabra.length < 3 || buscar.palabra == null ) {
                dispatch('getTablas', state.utimaRuta)
                // console.log('menos de 3 palabras, ultima ruta', state.utimaRuta)
            }
            else {
                // console.log('mas de 3 palabras')
                await axios.post(buscar.pagina , { search: buscar.palabra})
                    .then(response => {
                        // console.log('datos de la busqueda', response.data)
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
    },


}
export default {
    namespaced,
    state,
    getters,
    actions,
    mutations
}
