<template>
<v-skeleton-loader transition="scale-transition" type="table" class="mx-auto" :loading="loading">
    <v-card>
        <v-card-title>
            <v-text-field v-model="vmodel_buscar"
             hide-details class="col-12 col-md-4 col-lg-4"
             prepend-inner-icon="mdi-magnify"
             @keyup.enter="buscar(vmodel_buscar)"
             label="Buscar Podcast">
            </v-text-field>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined dark :to="{name: 'podcastAdd'}">Agregar Podcast</v-btn>
        </v-card-title>

        <v-card elevation="0">
            <v-tabs v-model="vmodel_tab">
                <v-tab>Publicados ({{ itemsCount }})</v-tab>
                <v-tab>Borradores ({{ getCount.trash }})</v-tab>
                <v-tab v-show="false">Papelera ({{ getCount.delete }})</v-tab>
            </v-tabs>

            <v-tabs-items v-model="vmodel_tab">
                <v-tab-item>
                    <!-- <podcast-data-table/> -->
                    <DataTablePodcast2Vue v-if="vmodel_tab ==0"
                    :listar="all"
                    />
                </v-tab-item>
                <v-tab-item>
                    <!-- <podcast-data-table/> -->
                    <DataTablePodcast2Vue v-if="vmodel_tab ==1"
                    :listar="trash"
                    />
                </v-tab-item>
                <v-tab-item>
                    <!-- <podcast-data-table/> -->
                    <DataTablePodcast2Vue v-if="vmodel_tab ==2"
                    :listar="remove"
                    />
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-card>
</v-skeleton-loader>
</template>

<script>
    import DataTablePodcast2Vue from "./DataTablePodcast2.vue";
import {
    mapActions,
    mapMutations,
    mapGetters
} from "vuex";

export default {
    props: [],
    components: {
        DataTablePodcast2Vue
    },
    data: () => ({
        all: 'all',
        trash: 'trash',
        remove: 'remove',

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
    computed: {
        ...mapGetters(['getCount', 'getDialogConfirStatus']),
        ...mapGetters('cat',['getTablas']),
        itemsCount: {
            get() {     return this.getTablas.total },
            set() {     return this.getTablas.total }
        },
    },
    methods: {
        ...mapMutations(['setTextSearch', 'setDesserts', 'setTab']),
        ...mapActions('cat',['buscarTablas']),
        buscar() {
            this.buscarTablas({
                palabra: this.vmodel_buscar,
                pagina: 1
            })
        },
        publicados() {
            console.log('Publicados')

            this.setDesserts([]);

            axios.get('podcast/list/all').then(response => {
                console.log(response.data)
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
            console.log('Borradores')

            this.setDesserts([]);
            axios.get('podcast/list/trash').then(response => {
                console.log(response.data)

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
                console.log(response.data)
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
