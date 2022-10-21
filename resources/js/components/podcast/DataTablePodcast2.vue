<template>
    <div id="app">
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
              <v-toolbar-title>count {{itemsCount}}</v-toolbar-title>
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
                  <v-card-title class="text-h5">Estas seguro de borrar este registro?</v-card-title>
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
        <!-- Diseño Programa -->
        <template v-slot:[`item.programa`]="{ item }">
            <v-chip class="ma-2" color="orange" outlined>
                {{item.programa}}
            </v-chip>
        </template>
        <!-- Diseño Productornombre -->
        <template v-slot:[`item.productornombre`]="{ item }">
            <v-chip class="ma-2" color="purple" outlined>
                {{item.productornombre}}
            </v-chip>
        </template>
        <!-- Diseño Estatus -->
        <template v-slot:[`item.estatus`]="{ item }">
            <v-chip v-if="item.estatus == 1" class="ma-2" c5olor="success" outlined>
                Publicado
            </v-chip>
            <v-chip v-else-if="item.estatus == 2" class="ma-2" color="primary" outlined>
                Borrador
            </v-chip>
            <v-chip v-else-if="item.estatus == 3" class="ma-2" color="error" outlined>
                Papelera
            </v-chip>
        </template>

        <!-- Diseño Acciones -->
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
    import PaginateVue from '../utils/Paginate.vue';
    import { mapState, mapGetters, mapActions } from 'vuex';

        export default {
          components: {
               PaginateVue,
         },
         props: {
            listar: String
         },
            name: 'DataTablesProgramas',
    data: () => ({

        loading:false,
        rutas: {
            urlGet:'podcast/list/',
            urlSearch:'podcast/search?page=',
            urlStore: 'podcast/publish',
            urlUpdate: 'podcast/eraser',
            urlDelete: 'podcast/trash',
        },
        page: 1,
        pageCount: 0,
        last_page:0,
        // dialog: false,
        dialogDelete: false,
        headers: [
            { text: 'Título', value: 'titulo', width: '25%' },
            { text: 'Programa', value: 'programa', },
            { text: 'Productor', value: "productornombre" },
            { text: 'Fecha publicación', value: 'created_at', },
            { text: 'Estatus', value: 'estatus', },
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
        itemsCount: {
            get() {     return this.getTablas.total },
            set() {     return this.getTablas.total }
        }
      },
      async created () {
            await this.cargarEstaciones()
          this.rutas.urlGet += this.listar
          console.log('Iniciando componente Programa', this.rutas.urlGet)
          await this.cambiarRutas(this.rutas)
          await this.initialize()
        //   console.log('Datos cargados... ', this.getTablas.data)
      },
      mounted() {
          this.cargarTablas()
        //   console.log(this.datos);
      },

      methods: {
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
            this.cargarTablas(1)
            this.loading = false
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
            this.$router.push({
                path: `/podcast/editar/${item.token}`
            });
        },
        borradorItem(item) {
            this.updateTabla({
                id: item.id,
                token: item.token,
            })
            this.$store.dispatch('countPodcast');

        },
        publishItem(item) {
            this.storeTabla({
                id: item.id,
                token: item.token,
            })
            this.$store.dispatch('countPodcast');
        },
        confirmarPapeleraItem(item) {
            this.editedItem = item
            // console.log('item confirmado', this.editedItem)
            this.dialogDelete = true
        },
        deleteItemConfirm() {
            // console.log('eliminando', this.editedItem.token )
            this.postTabla({
                url: 'podcast/trash',
                data: {
                    token: this.editedItem.token,
                }
            })
            this.$store.dispatch('countPodcast');
            this.closeDelete()
        },
        closeDelete() {
            this.dialogDelete = false
        }
    },
}
</script>

