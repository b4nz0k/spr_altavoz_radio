<template>
<!-- v-model="selected" show-select  -->
<!-- :single-expand="singleExpand" :expanded.sync="expanded" show-expand item-key="id" -->
<v-data-table
:headers="headers"
:search="getTextSearch"
:items="getDesserts"
class="elevation-0"
:items-per-page="10"
:footer-props="{'items-per-page-options':
 [10, 20, 30, 40, 50, -1]}">

    <template v-slot:[`item.usuario`]="{ item }">
        <v-chip class="ma-2" color="primary" outlined>
            {{item.usuario}}
        </v-chip>
    </template>
    <template v-slot:[`item.estaciones_ids`]="{ item }">
        <v-tooltip bottom color="success">
            <template v-slot:activator="{ on, attrs }">

                    <v-chip class="ma-2" outlined
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    >
                    <v-badge
                        color="primary"
                        :content="item.estaciones_lista.length"
                        overlap
                        inline
                        >
                        Estaciones
                    </v-badge>
                    </v-chip>
            </template>
            <span v-for="(it, index) in item.estaciones_lista" :key="index">
                {{it}} - {{setEstaciones(it)}}<br/>
            </span>
        </v-tooltip>
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
                <span>Mover a Borrador {{item.titulo}}</span>
            </v-tooltip>
        </span>

        <span v-if="item.estatus == 1 || item.estatus == 2">
            <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn v-if="infoUsuario.nivel > 2" color="error" v-bind="attrs" v-on="on" fab x-small @click="confirmarPapeleraItem(item, 'trash')">
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
                    <v-btn v-if="infoUsuario.nivel > 2" color="error" v-bind="attrs" v-on="on" fab x-small @click="confirmarPermanenteItem(item, 'remove')">
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
        headers: [

        {
            text: 'T??tulo',
            value: 'titulo',
            width: '25%',
        },
        {
            text: 'Estaci??n Radio',
            value: 'estaciones_id',
        },
        {
            text: 'Autor',
            value: "autor"
        },
        {
            text: 'Fecha publicaci??n',
            value: 'created_at',
        },
        {
            text: 'Estatus',
            value: 'name_estatus',
        },
        {
            text: 'Acciones',
            value: 'actions',
            sortable: false,
            width:'18%'
        },
    ],
        selected: [],
        selectedItem: '',
        accion: '',
        singleExpand: false,
        expanded: [],
        estaciones:[],
    }),
    created() {
        this.cargarEstaciones();
    },
    mounted() {},
    computed: {
        ...mapGetters(['getTextSearch', 'getDesserts', 'getTab', 'getDialogConfirStatus']),
        ...mapGetters(['infoUsuario',]),
        ...mapState({
        user: ({ auth: { info_usuario } }) => info_usuario,
        }),

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
        async cargarEstaciones() {
            await axios.get('estaciones/list').then(response => {
                this.estaciones= response.data;
                // console.log('Cargar Estaciones: ',this.estaciones)
        })

        },
        setEstaciones(id) {
            let getEstacion
            if (getEstacion= this.estaciones.find(item => item.id == id))
                return getEstacion.estacion
            return 'Sin Estacion'
        },
        publicados() {
            //this.setDessertsPrograma([]);
            axios.get('programa/list/all').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.$store.dispatch('countProgramas');
                    this.loading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        borradores() {
            //this.setDessertsPrograma([]);
            axios.get('programa/list/trash').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.$store.dispatch('countProgramas');
                    this.loading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        papelera() {
            //this.setDessertsPrograma([]);
            axios.get('programa/list/remove').then(response => {
                if (!response.data.answer) {
                    this.setDesserts(response.data);
                    this.$store.dispatch('countProgramas');
                    this.loading = false;
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        confirmarRestoreItem(item, accion) {
            this.$store.dispatch('dialogConfirmacionDelete', ['Confirmaci??n', '??Est?? seguro de que desea restuarar a ' + item.titulo + '?', true]);
            this.selectedItem = item;
            this.accion = accion;
        },

        confirmarPapeleraItem(item, accion) {

            // console.log(item)
            axios.post('programa/records', {
                token: item.token,
            }).then(response => {
                let records = response.data;
                if (records > 0) {
                    this.$store.dispatch('dialogConfirmacionDelete', ['Confirmaci??n', 'Existen ' + records + ' podcast adjuntas a este programa ??Est?? seguro de que desea enviar a papelera ' + item.titulo + '?', true]);
                } else {
                    this.$store.dispatch('dialogConfirmacionDelete', ['Confirmaci??n', '??Est?? seguro de que desea enviar a papelera ' + item.titulo + '?', true]);
                }
                this.selectedItem = item;
                this.accion = accion;
            }).catch(error => {
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        confirmarPermanenteItem(item, accion) {
            axios.post('programa/records', {
                token: item.token,
            }).then(response => {
                let records = response.data;
                if (records > 0) {
                    this.$store.dispatch('dialogConfirmacionDelete', ['Confirmaci??n', 'Existen ' + records + ' podcast adjuntas a este programa ??Est?? seguro de que desea borrar permanantemente a ' + item.titulo + '?', true]);
                } else {
                    this.$store.dispatch('dialogConfirmacionDelete', ['Confirmaci??n', '??Est?? seguro de que desea borrar permanantemente a ' + item.titulo + '?', true]);
                }
                this.selectedItem = item;
                this.accion = accion;
            }).catch(error => {
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        restoreItem(item) {
            axios.post('programa/restore', {
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
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        deletePapeleraItem(item) {
            axios.post('programa/trash', {
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
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        deletePermanenteItem(item) {
            axios.post('programa/remove', {
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
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        publishItem(item) {
            // console.log('Click en publicar ', item);
            axios.post('programa/publish', {
                id: item.id,
                token: item.token,
            }).then(response => {
                // console.log(response.data)
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    this.borradores();
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        borradorItem(item) {
            axios.post('programa/eraser', {
                id: item.id,
                token: item.token,
            }).then(response => {
                // console.log('el item es ',item)
                // console.log('CLick en borrador', response.data);
                if (response.data.answer) {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                    this.publicados();
                } else {
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticaci??n, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la p??gina por favor.', 'error', true, '', []]);
                }
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\programa\\DataTableComponent.vue'
                }).catch(error => {});
            });
        },

        editItem(item) {
            this.$router.push({
                path: `/programa/editar/${item.token}`
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
