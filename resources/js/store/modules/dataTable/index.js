const state = {
    desserts: [],
    text_search: '',
    count: [],
    tab: null,
};

const getters = {
    getTextSearch: state => {
        return state.text_search;
    },

    getDesserts: state => {
        return state.desserts;
    },

    getCount: state => {
        return state.count;
    },

    getTab: state => {
        return state.tab;
    }
};

const actions = {
    countProgramas(context) {
        axios.get('programa/count').then(response => {
            context.commit('setCount', response.data); 
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
                file: 'store\\modules\\dataTable\\index.js'
            }).catch(error => {});
        });
    },

    countPodcast(context) {
        axios.get('podcast/count').then(response => {
            context.commit('setCount', response.data); 
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
                file: 'store\\modules\\dataTable\\index.js'
            }).catch(error => {});
        });
    },

    countBlog(context) {
        axios.get('blog/count').then(response => {
            context.commit('setCount', response.data); 
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
                file: 'store\\modules\\dataTable\\index.js'
            }).catch(error => {});
        });
    },

    countMultimedia(context) {
        axios.get('multimedia/count').then(response => {
            context.commit('setCount', response.data); 
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
                file: 'store\\modules\\dataTable\\index.js'
            }).catch(error => {});
        });
    },
};

const mutations = {
    setTextSearch(state, search) {
        state.text_search = search;
    },
    
    setDesserts(state, desserts) {
        state.desserts = desserts;
    },

    setCount(state, count) {
        state.count = count;
    },

    setTab(state, tab) {
        state.tab = tab;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}