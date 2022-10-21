<template>
    <div id="app">
    <v-alert
    v-if="msjError"
    class="mb-2"
      shaped
      dense
      dark
      type="warning"
    >
      No pudimos cargar la pagina, intentando reparar la base de datos
    </v-alert>
        Contando {{getTablas.total}}
        <v-data-table
          :loading="loading"
          :headers="headers"
          :items="listaDatos"
          :items-per-page="itemsPerPage"
          hide-default-footer
          sort-by="id"
          class="elevation-1"
        > <!-- Estatus -->
          <template v-slot:top>
            <v-toolbar

            >
            <v-spacer></v-spacer>

              <v-divider
                class="mx-4"
                inset
                vertical
              ></v-divider>
              <v-spacer></v-spacer>

              <!-- Proximo Componente  -->

              <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card>
                    <v-toolbar dark
                    color="primary"
                    ><span class="text-h5">
                       Confirmacion
                    </span></v-toolbar>
                  <v-card-title class="text-h5">
                      ¿Esta seguro?
                    </v-card-title>
                        <v-card-text class="text-h5">
                            Se eliminaran {{ dialogDeleteCount }} registros
                        </v-card-text>
                        <v-card-text  class="text-h5"
                        v-if="dialogDeleteCountPodcast > 0">
                            Consigo se eliminaran {{dialogDeleteCountPodcast}} Podcast
                        </v-card-text>




                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" @click="closeDelete">Cancelar</v-btn>
                    <v-btn color="error" @click="deleteItemConfirm">Si</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>

            </v-toolbar>
        </template>
        <!-- v-slot -->
        <!-- <template v-slot:[`item.imagen_destacada`]="{ item }">
            <v-row  >
                                    <v-img
                                    lazy-src="/img/dont_found.png"
                                    :src="item.imagen_destacada"
                                    max-width="50"
                                    max-height="80"
                                    >
                                    <template v-slot:placeholder>
                                        <v-row
                                        class="fill-height ma-0"
                                        align="center"
                                        justify="center"
                                        >
                                        <v-progress-circular
                                            indeterminate
                                            color="grey lighten-5"
                                        ></v-progress-circular>
                                        </v-row>
                                    </template>
                                    </v-img>
                                </v-row>

        </template> -->

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
                    <v-btn v-if="infoUsuario.nivel > 2" color="error" v-bind="attrs" v-on="on" fab x-small @click="confirmarPapeleraItem(item)">
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


        </v-data-table>
        <!-- Paginador -->
        <PaginateVue/>
    </div>
    </template>

    <script>
    import PaginateVue from '..//utils/Paginate.vue';
    import { mapState, mapGetters, mapActions, mapMutations } from 'vuex';

        export default {
          components: {
               PaginateVue,
         },
         props: {
            listar: String
         },
            name: 'DataTablesProgramas',
    data: () => ({

        loading:true,
        rutas: {
            urlGet:'programa/list/',
            urlSearch:'programa/search?page=',
            urlStore: 'programa/publish',
            urlUpdate: 'programa/eraser',
            urlDelete: 'programa/trash',
        },
        page: 1,
        pageCount: 0,
        last_page:0,
        // dialog: false,
        msjError: false,
        errorMsj: '',
        dialogError: false,
        dialogDelete: false,
        dialogDeleteCount: 0,
        dialogDeleteCountPodcast: 0,
        headers: [
            { text: 'ID', value: 'id', width: '5%' },
            { text: 'Título', value: 'titulo', width: '25%' },
            // { text: 'img', value: 'imagen_destacada', },
            { text: 'Autor', value: "autornombre" },
            { text: 'Fecha Publicación', value: 'creacion', },
            { text: 'Estatus', value: 'name_estatus', },
            { text: 'Acciones', value: 'actions', sortable: false, width:'18%' },
        ],
        // categorias: [],
        search: '',
        editedItem: [],
        estaciones: [],

      }),
      computed: {
        ...mapState('cat',['datos', 'dialogDel']),
        ...mapGetters('cat', ['getItem', 'getTablas']),
        ...mapGetters(['infoUsuario']),
        listaDatos: {
            get() {     return this.getTablas.data      },
            set() {     return this.getTablas.data      }
        },
        itemsPerPage: {
            get() {    return this.datos.per_page    }
        },
      },
      async created () {
        await this.cargarEstaciones()
          this.rutas.urlGet += this.listar
          console.log('Iniciando componente Programa', this.rutas.urlGet)
        //   console.log('Datos consultados', this.getTablas.data)
        await this.cambiarRutas(this.rutas)
        await this.initialize()
      },
      mounted() {
          this.cargarTablas()
        //   console.log(this.datos);
      },

      methods: {
        ...mapMutations('cat', ['setItem']),
        ...mapActions('cat',[
            'cargarTablas', 'buscarTablas', 'eliminarTabla', 'updateTabla', 'storeTabla',
            , 'cambiarTitulo', 'cambiarDialogDel', 'cargarItem', 'cambiarRutas', 'postTabla'
        ]),
        async cargarEstaciones() {
            await axios.get('estaciones/list').then(response => {
                this.estaciones= response.data;
                // console.log('Cargar Estaciones: ',this.estaciones)
            })
        },
        setEstaciones(id) {
            let getEstacion
            if (getEstacion = this.estaciones.find(item => item.id == id)) {
                return getEstacion.estacion
            }
            else {
                return 'Sin Estacion'
            }
        },
        initialize () {
            this.loading = true
            this.corregirDatos()
            this.cargarTablas(1)
            this.loading = false
        },

        async corregirDatos() {
            // console.log('desde el cliente, ver si los datos estan correctos')
            // await axios.get('programas/comprobar')
            //     .then(response => {
            //         if (response.answer == false) {
            //             this.errorMsj = response.msj
            //             this.msjError = true

            //         }
            //         console.log(response.log)
            //     })
        },
        buscar() {
            this.loading = true
            this.buscarTablas({
                palabra: this.search,
                pagina: 1
            })
            this.loading = false
        },
        editItem (item) {
            // console.log('item... ',item);
            this.setItem(item);
            // console.log(this.getItem)
                this.$router.push({
                    path: `/programa/editar/${item.token}`
                });


        },
        borradorItem(item) {
            this.updateTabla({
                id: item.id,
                token: item.token,
            })
            this.$store.dispatch('countProgramas');
            this.$store.dispatch('countPodcast');

        },
        publishItem(item) {
            this.storeTabla({
                id: item.id,
                token: item.token,
            })
            this.$store.dispatch('countProgramas');
            this.$store.dispatch('countPodcast');
        },
        confirmarPapeleraItem(item) {
            this.editedItem = item
            // console.log('Eliminar Item', this.editedItem)
            axios.post('programa/trash', {
                        id: this.editedItem.id,
                        token: this.editedItem.token,
                    }).then(response => {
                        // console.log(response.data)
                        this.dialogDeleteCount = response.data.msg
                        this.dialogDeleteCountPodcast = response.data.podcast
                        this.dialogDelete = true
                })
        },
        deleteItemConfirm() {
            // console.log('eliminando... ', this.editedItem )
            this.postTabla({
                url: 'programa/remove',
                data: {
                    id: this.editedItem.id,
                    token: this.editedItem.token,
                }
            })
            this.$store.dispatch('countProgramas');
            this.closeDelete()
        },
        closeDelete() {
            this.dialogDelete = false
        }
    },
}

    </script>

