<template>
<v-skeleton-loader transition="scale-transition" type="table" class="mx-auto" :loading="loading">
    <v-card>
        <v-card-title>
            <v-text-field
                v-model="vmodel_buscar"
                hide-details class="col-12 col-md-4 col-lg-4"
                prepend-inner-icon="mdi-magnify"
                @keyup.enter="buscar(vmodel_buscar)"
                label="Buscar Programa">
            </v-text-field>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined dark :to="{name: 'programaAdd'}">Agregar Programa</v-btn>
        </v-card-title>

        <v-card elevation="0">
            <v-tabs v-model="vmodel_tab">
                <v-tab>Publicados ({{ getCount.publish }})</v-tab>
                <v-tab>Borradores ({{ getCount.trash }})</v-tab>
                <v-tab v-show="false">Papelera ({{ getCount.delete }})</v-tab>
            </v-tabs>

            <v-tabs-items v-model="vmodel_tab">
                <v-tab-item>
                    <DataTable2Vue v-if="vmodel_tab ==0"
                    :listar="all"/>
                    <!-- <programa-data-table></programa-data-table> -->
                </v-tab-item>
                <v-tab-item >
                    <DataTable2Vue  v-if="vmodel_tab ==1"
                    :listar="trash"/>
                    <!-- <programa-data-table></programa-data-table> -->
                </v-tab-item>
                <v-tab-item v-show="false">
                    <DataTable2Vue
                    :listar="remove"/>
                    <!-- <programa-data-table></programa-data-table> -->
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-card>
</v-skeleton-loader>
</template>

<script>
    import DataTable2Vue from "./DataTable2.vue";
import {
    mapState,
    mapMutations,
    mapGetters,
    mapActions
} from "vuex";

export default {
    props: [],
    components: {
        DataTable2Vue
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
        this.$store.dispatch('countProgramas');
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
            this.setTab(val);
        },

        vmodel_buscar(val) {
            this.setTextSearch(val);
        }
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
            this.setDesserts([]);
            axios.get('programa/list/all').then(response => {
                // console.log('publicados', response.data)
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
                    file: 'components\\programa\\ListProgramaComponent.vue'
                }).catch(error => {});
            });
        },

        borradores() {
            this.setDesserts([]);
            axios.get('programa/list/trash').then(response => {
                    // console.log('borradores', response.data)
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
                    file: 'components\\programa\\ListProgramaComponent.vue'
                }).catch(error => {});
            });
        },

        papelera() {
            this.setDesserts([]);
            axios.get('programa/list/remove').then(response => {
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
                    file: 'components\\programa\\ListProgramaComponent.vue'
                }).catch(error => {});
            });
        },
    },
}
</script>
