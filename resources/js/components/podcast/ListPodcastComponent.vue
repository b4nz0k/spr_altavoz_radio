<template>
<v-skeleton-loader transition="scale-transition" type="table" class="mx-auto" :loading="loading">
    <v-card>
        <v-card-title>
            <v-text-field v-model="vmodel_buscar" hide-details class="col-12 col-md-4 col-lg-4" prepend-inner-icon="mdi-magnify" label="Buscar Podcast"></v-text-field>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined dark :to="{name: 'podcastAdd'}">Agregar Podcast</v-btn>
        </v-card-title>

        <v-card elevation="0">
            <v-tabs v-model="vmodel_tab">
                <v-tab>Publicados ({{ getCount.publish }})</v-tab>
                <v-tab>Borradores ({{ getCount.trash }})</v-tab>
                <v-tab>Papelera ({{ getCount.delete }})</v-tab>
            </v-tabs>

            <v-tabs-items v-model="vmodel_tab">
                <v-tab-item>
                    <podcast-data-table></podcast-data-table>
                </v-tab-item>
                <v-tab-item>
                    <podcast-data-table></podcast-data-table>
                </v-tab-item>
                <v-tab-item>
                    <podcast-data-table></podcast-data-table>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-card>
</v-skeleton-loader>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex";

export default {
    props: [],
    components: {},
    data: () => ({
        loading: true,
        selected: [],
        vmodel_buscar: '',
        vmodel_tab: 0,
    }),
    beforeCreate() {},
    created() {
        this.$store.dispatch('countPodcast');
        this.publicados();
    },
    mounted() {},
    computed: {
        ...mapGetters(['getCount', 'getDialogConfirStatus']),
    },
    watch: {
        vmodel_tab(val) {
            if (val == 0) {
                this.publicados();
            } else if (val == 1) {
                this.borradores();
            } else if (val == 2) {
                this.papelera();
            }
        }, 

        vmodel_buscar(val) {
            this.setTextSearch(val);
        }
    },
    methods: {
        ...mapMutations(['setTextSearch', 'setDesserts']),

        publicados() {
            this.setDesserts([]);
            axios.get('podcast/list/all').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.loading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\podcast\\ListPodcastComponent.vue'
                }).catch(error => {});
            });
        },

        borradores() {
            this.setDesserts([]);
            axios.get('podcast/list/trash').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.loading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\podcast\\ListPodcastComponent.vue'
                }).catch(error => {});
            });
        },

        papelera() {
            this.setDesserts([]);
            axios.get('podcast/list/remove').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.loading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\podcast\\ListPodcastComponent.vue'
                }).catch(error => {});
            });
        },
    },
}
</script>
