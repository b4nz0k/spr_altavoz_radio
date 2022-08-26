<template>
<!-- v-model="selected" show-select  -->
<!-- :single-expand="singleExpand" :expanded.sync="expanded" show-expand item-key="id" -->
<v-data-table :headers="headers" :search="getTextSearch" :items="getDesserts" class="elevation-0" :items-per-page="10" :footer-props="{'items-per-page-options': [10, 20, 30, 40, 50, -1]}">
    <!-- <template v-slot:expanded-item="{ item }">
        <td colspan="12" class="pa-0">
            <v-simple-table style="width:100%" class="elevation-4">
                <template v-slot:default>
                    <thead>
                        <tr class="grey lighten-3">
                            <th>Estacion(es) de Radio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <v-chip class="ma-2" color="primary" small v-for="estacion in item.estacion" :key="estacion.id">
                                    {{estacion.estacion}}
                                </v-chip>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </td>
    </template> -->

    <template v-slot:[`item.categoria`]="{ item }">
        <div class="col-8 text-truncate">{{ item.categoria }}</div>
    </template>

    <template v-slot:[`item.usuario`]="{ item }">
        <v-chip class="ma-2" color="primary" outlined>
            {{item.usuario}}
        </v-chip>
    </template>

    <template v-slot:[`item.name_estatus`]="{ item }">
        <v-chip v-if="item.estatus == 1" class="ma-2" color="success" outlined>
            {{item.name_estatus}}
        </v-chip>
        <v-chip v-else-if="item.estatus == 2" class="ma-2" color="primary" outlined>
            {{item.name_estatus}}
        </v-chip>
        <v-chip v-else-if="item.estatus == 3" class="ma-2" color="error" outlined>
            {{item.name_estatus}}
        </v-chip>
    </template>

    <template v-slot:[`item.actions`]="{ item }">
        <span v-if="item.estatus == 1 || item.estatus == 2">
            <!-- <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon class="mr-2" v-bind="attrs" v-on="on" @click="verItem(item)">mdi-eye</v-icon>
                </template>
                <span>Ver {{item.titulo}}</span>
            </v-tooltip> -->
            <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon class="mr-2" v-bind="attrs" v-on="on" @click="editItem(item)">mdi-pencil</v-icon>
                </template>
                <span>Editar {{item.titulo}}</span>
            </v-tooltip>
            <v-tooltip top v-if="item.estatus == 2">
                <template v-slot:activator="{ on, attrs }">
                    <v-icon class="mr-2" v-bind="attrs" v-on="on" @click="publishItem(item)">mdi-check-bold</v-icon>
                </template>
                <span>Publicar {{item.titulo}}</span>
            </v-tooltip>
        </span>

        <span v-if="item.estatus == 1">
            <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon class="mr-2" v-bind="attrs" v-on="on" @click="borradorItem(item)">mdi-eraser</v-icon>
                </template>
                <span>Borrador {{item.titulo}}</span>
            </v-tooltip>
        </span>

        <span v-if="item.estatus == 1 || item.estatus == 2">
            <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="error" v-bind="attrs" v-on="on" fab x-small @click="confirmarPapeleraItem(item, 'trash')">
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </template>
                <span>Enviar a papelera {{item.titulo}}</span>
            </v-tooltip>
        </span>

        <span v-if="item.estatus == 3">
            <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn v-bind="attrs" v-on="on" text fab icon @click="confirmarRestoreItem(item, 'restore')">
                        <v-icon>mdi-history</v-icon>
                    </v-btn>
                </template>
                <span>Restaurar {{item.titulo}}</span>
            </v-tooltip>
            <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="error" v-bind="attrs" v-on="on" fab x-small @click="confirmarPermanenteItem(item, 'remove')">
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </template>
                <span>Borrar permanentemente {{item.titulo}}</span>
            </v-tooltip>
        </span>
    </template>

    <template v-slot:no-data>
        No se encontraron registros existentes
    </template>

</v-data-table>
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
        headers: [{
                text: 'Título',
                value: 'titulo',
                width: '120',
            },
            {
                text: 'Programa',
                value: 'programa',
                width: '320',
            },
            {
                text: 'Categoría',
                value: 'categoria',
            },
            {
                text: 'Usuario',
                value: 'usuario'
            },
            {
                text: 'Fecha publicación',
                value: 'created_at',
            },
            {
                text: 'Estatus',
                value: 'name_estatus',
            },
            {
                text: 'Acciones',
                value: 'actions',
                sortable: false
            },
        ],
        selected: [],
        selectedItem: '',
        accion: '',
        singleExpand: false,
        expanded: [],
    }),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {
        ...mapGetters(['getTextSearch', 'getDesserts', 'getTab', 'getDialogConfirStatus']),
    },
    watch: {
        getDialogConfirStatus(val) {
            if (val) {
                this.methodAcciones(this.selectedItem, this.accion);
            }
        },
    },
    methods: {
        ...mapMutations(['setDesserts', 'setDialogConfirStatus']),

        publicados() {
            //this.setDessertsPrograma([]);
            axios.get('podcast/list/all').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.$store.dispatch('countPodcast');
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        borradores() {
            //this.setDessertsPrograma([]);
            axios.get('podcast/list/trash').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.$store.dispatch('countPodcast');
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        papelera() {
            //this.setDessertsPrograma([]);
            axios.get('podcast/list/remove').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.$store.dispatch('countPodcast');
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        confirmarRestoreItem(item, accion) {
            this.$store.dispatch('dialogConfirmacionDelete', ['Confirmación', '¿Está seguro de que desea restuarar a ' + item.titulo + '?', true]);
            this.selectedItem = item;
            this.accion = accion;
        },

        confirmarPapeleraItem(item, accion) {
            this.$store.dispatch('dialogConfirmacionDelete', ['Confirmación', '¿Está seguro de que desea enviar a papelera ' + item.titulo + '?', true]);
            this.selectedItem = item;
            this.accion = accion;
        },

        confirmarPermanenteItem(item, accion) {
            this.$store.dispatch('dialogConfirmacionDelete', ['Confirmación', '¿Está seguro de que desea borrar permanantemente a ' + item.titulo + '?', true]);
            this.selectedItem = item;
            this.accion = accion;
        },

        restoreItem(item) {
            axios.post('podcast/restore', {
                token: item.token,
            }).then(response => {
                this.setDialogConfirStatus(false);
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    this.papelera();
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        deletePapeleraItem(item) {
            axios.post('podcast/trash', {
                token: item.token,
            }).then(response => {
                this.setDialogConfirStatus(false);
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    if (this.getTab == 0) {
                        this.publicados();
                    } else {
                        this.borradores();
                    }
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        deletePermanenteItem(item) {
            axios.post('podcast/remove', {
                token: item.token,
            }).then(response => {
                this.setDialogConfirStatus(false);
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    this.papelera();
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        publishItem(item) {
            axios.post('podcast/publish', {
                token: item.token,
            }).then(response => {
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    this.borradores();
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        borradorItem(item) {
            axios.post('podcast/eraser', {
                token: item.token,
            }).then(response => {
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    this.publicados();
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
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
                    file: 'components\\podcast\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        editItem(item) {
            this.$router.push({
                path: `/podcast/editar/${item.token}`
            });
        },

        methodAcciones(item, accion) {
            if (item.estatus == 3) {
                if (accion == 'restore') {
                    this.restoreItem(item);
                } else if (accion == 'remove') {
                    this.deletePermanenteItem(item);
                }
            } else if (item.estatus == 2) {
                this.deletePapeleraItem(item);
            } else if (item.estatus == 1) {
                this.deletePapeleraItem(item);
            }
        },
    },
}
</script>
