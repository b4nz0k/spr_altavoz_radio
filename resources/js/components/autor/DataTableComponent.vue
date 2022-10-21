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
              <v-toolbar-title>Autores</v-toolbar-title>
                         <v-spacer></v-spacer>
                <v-text-field
                v-model="search"
                @keyup.enter="buscar"
                align-center
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
              ></v-text-field>

              <v-divider
                class="mx-4"
                inset
                vertical
              ></v-divider>
              <v-spacer></v-spacer>
              <v-card-title>
                <v-btn color="primary"
                        outlined
                        dark
                        class="mb-2"
                        @click="nuevoReg"
                        > Nuevo Registro
               </v-btn>
              </v-card-title>


                <!-- Componente DialogOpcion -->
                <DialogOpcion
                :close="close"
                :save="save"
                />
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
                    <v-btn  color="info" @click="closeDelete">Cancelar</v-btn>
                    <v-btn v-if="infoUsuario.nivel > 2" color="error" @click="deleteItemConfirm">Si</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>

            </v-toolbar>
          </template>


          <template v-slot:[`item.actions`]="{ item }">

            <a
            @click="editItem(item)"
            class="btn btn-outline-primary">
            <v-icon>
                mdi-pencil
            </v-icon>
            </a >

            <a
            v-if="infoUsuario.nivel > 2"
                @click="deleteItem(item)"
                type="button"
                class="btn btn-danger " >
                <v-icon color="error">mdi-delete</v-icon>

             </a>
          </template>
        </v-data-table>
        <!-- Paginador -->
        <PaginateVue/>
    </div>
    </template>

    <script>
    import DialogOpcion from './DialogOpcion.vue';
    import PaginateVue from '../utils/Paginate.vue';
    import { mapState, mapGetters, mapActions } from 'vuex';
import { set } from 'vue';

        export default {
          components: {
               PaginateVue,
               DialogOpcion
         },
            name: 'listacategorias',
    data: () => ({

        loading:false,
        rutas: {
            urlGet:'autor/list',
            urlSearch:'autor/search?page=',
            urlStore: 'autor/add',
            urlUpdate: 'autor/edit',
            urlDelete: 'autor/remove/',
        },
        page: 1,
        pageCount: 0,
        last_page:0,
        // dialog: false,
        dialogDelete: false,
        headers: [
          { text: 'ID', value: 'id' }, //id
          { text: 'Nombre', align: 'start', sortable: false, value: 'autor',  },
          { text: 'Actions', value: 'actions', sortable: false, width: '20%' },
        ],
        // categorias: [],
        search: '',
        editedIndex: -1,
        editedItem: {
          id: 0,
          autor: '',
        },
        defaultItem: {
          id: 0,
          autor: '',
        },

      }),
      computed: {
        ...mapState('cat',['datos', 'dialogDel']),
        ...mapGetters('cat', ['getItem', 'getTablas']),
        ...mapGetters(['infoUsuario']),
        listaDatos: {
            get() {   return this.getTablas.data       },
            set() {
                return this.getTablas.data
            }
        },
        itemsPerPage: {
            get() {    return this.datos.per_page            }
        },
    //     dialogDelete: {
    //             get() {  return this.dialogDel                 },
    //             set() {  return this.cambiarDialogDel(false)  }
    //         },
      },
      async created () {
        console.log('Iniciando componente autores')
        await this.cambiarRutas(this.rutas)
        await this.initialize()
      },
      mounted() {
          this.cargarTablas()
        //   console.log(this.datos);
      },

      methods: {
        ...mapActions('cat',[
            'cargarTablas', 'buscarTablas', 'eliminarTabla', 'updateTabla', 'storeTabla',
            'cambiarDialogOpcion', 'cambiarTitulo', 'cambiarDialogDel', 'cargarItem', 'cambiarRutas'
        ]),

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
        nuevoReg() {
            this.cambiarTitulo('Nuevo Registro')
            this.cambiarDialogOpcion(true)
            this.cargarItem(this.defaultItem)
        },
        editItem (item) {
            // console.log('item... ',item);
            this.cambiarTitulo('Editar Registro')
            this.editedIndex = item.id
            this.cargarItem(Object.assign({}, item))
            this.cambiarDialogOpcion(true)
        },

        deleteItem (item) {
          this.editedIndex = this.datos.data.indexOf(item)
          this.cargarItem(Object.assign({}, item))
          this.dialogDelete = true
        },

        deleteItemConfirm () {
            this.eliminarTabla(this.getItem.id)
            this.initialize()
            this.closeDelete()

        },
        close () {
            this.cambiarDialogOpcion(false)
            this.cargarItem(Object.assign({}, this.defaultItem))
            this.editedIndex = -1
            this.initialize()

        },

        closeDelete () {
          this.dialogDelete = false
          this.cargarItem(Object.assign({}, this.defaultItem))
        this.editedIndex = -1
        },
        save () {
          if (this.editedIndex > -1) {
            // console.log('editando...', this.editedItem);
            this.updateTabla(this.getItem)
            this.close()

            // console.log('ahora en el store hay ', this.getItem)
          } else {
            // console.log('agregando...', this.editedIndex)
            this.storeTabla(this.getItem)
            this.close()

          }
        },

      },
        }

    </script>

