<template>
    <div>
<!-- SelectPaginate ||
cuantos {{getItems.total}}
pagina {{pagina}} ||
search {{search}} ||
select {{itemSelect}}-->
    <v-autocomplete
    @click="inicio"
    :search-input.sync="search"
    :loading="loading"
    v-model="itemSelect"
    :label="itemLabel"
    :items="localItems.data"
    :item-value="itemValue"
    :item-text="itemText"
    outlined
    @keyup.enter="buscar"
    >
    <div
    slot="prepend-item"
    class="grey--text">
        <div class="d-flex justify-content-center" >
        {{ getItems.total }} Elementos encontrados, elementos por pagina {{getItems.per_page}}
        </div>
        <div class="d-flex justify-content-between" >
            <v-btn rounded small :disabled="filtrosprev" @click="hasPrevPage" >
                <v-icon
                large
                >
                mdi-chevron-left
                </v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn rounded small :disabled="filtrosnext" @click="hasNextPage" >
            <v-icon
                large
                >
                mdi-chevron-right
                </v-icon>
            </v-btn>
          </div>
          <v-divider></v-divider>
    </div>
  <template v-if="slotTipo=='programas'" v-slot:item="{ item }">
    <span>{{item.titulo}}</span>
    <v-chip
      class="ma-2"
      color="green"
      text-color="white"
    >
    <span>{{item.creacion}}</span>
    </v-chip>
    </template>
  <template v-if="slotTipo=='productor'" v-slot:selection="data">
    <v-avatar color="indigo">
        <v-icon dark>
            mdi-account-circle
        </v-icon>
    </v-avatar>
    {{ data.item.nombre }}
</template>


  <template v-slot:append-item>

    </template>
    </v-autocomplete>
    </div>
  </template>

  <script>
import { mapActions, mapGetters, mapState } from 'vuex'
  export default {
    props: {
        itemModel:[Number, String, Date],
        paginacion: String,
        paginacionSearch: String,
        itemValue: String,
        itemText: String,
        itemLabel:String,
        retornarItem: Function,
        slotTipo: String,
    },
    data: () => ({
    itemSelect: null,
    localItems:{},
      search: '',
      offset: 0,
      limit: 10,
      pagina: 1,
      busqueda: false,
      paginaSearch: 1,
    }),
    watch: {
        itemSelect(NuevoValor, oldValor) {
                // console.log('NuevoValor', NuevoValor);
                // console.log('Viejo valor', oldValor);
                this.retornarItem(NuevoValor)
        }

    },
    created () {
this.inicio()
    },
    mounted() {
    },
    computed: {
        ...mapState('select', ['items']),
        ...mapGetters('select', ['getItems']),
        selectItem: {
            get() {
                return this.itemSelect
            },
            set() {
                // console.log('cambio el item select', this.itemSelect)
                return this.itemSelect
            }
        },
        filtrosprev() {
            if (this.getItems.current_page == 1) {
                return true
            }
            else {
                return false
            }
         },
        filtrosnext() {
            if (this.getItems.current_page == this.getItems.last_page) {
                return true
            }
            else {
                return false
            }
         },
         loading() {
            if (this.getItems.total > 0 ) {
                return false
            }
            else{
                true
            }
         }
    },
    methods: {
        ...mapActions('select', ['getTablas', 'postTablas', 'buscarTablas']),
        async inicio() {
           await this.getPaginacion(this.paginacion)
           this.localItems = this.getItems
           this.itemSelect = this.itemModel
        //    console.log('itemSelect', this.itemSelect)

        },
        async getPaginacion () {
            try {
                if (this.busqueda) {
                    await this.postPaginacion()
                    this.localItems = this.getItems
                }
                else {
                    await this.getTablas(this.paginacion + this.pagina )
                    this.localItems = this.getItems

                    // console.log('despues de cargar getTablas', this.items)
                }
            } catch (error) {
                console.log(error);
            };
        },
        async postPaginacion () {
            try {
                // console.log('entrando al post  ', this.paginacionSearch + this.pagina)
                await this.buscarTablas( {
                    palabra: this.search,
                    pagina: this.paginacionSearch+this.pagina
                })
                this.localItems = this.getItems

                // console.log('despues de cargar postTablas', this.items)
            } catch (error) {
                console.log(error);
            };
        },
        onSearch(query) {
            this.search = query
            this.offset = 0
        },
        hasNextPage() {
            this.pagina += 1
                    this.getPaginacion()

            // console.log('asd', this.getItems.current_page)
        },
        hasPrevPage() {
            if (this.pagina == 1) {
                this.pagina = 1
                    this.getPaginacion()
            }
            else {
                this.pagina -= 1
                    this.getPaginacion()
                }
        },
        async buscar() {
            try {
            if (this.search < 2) {
                this.busqueda = false
                this.getPaginacion()
            }
            else {
                console.log('Buscando ' ,this.search)
                this.pagina = 1
                this.busqueda = true
                await this.buscarTablas( {
                    palabra: this.search,
                    pagina: this.paginacionSearch+this.pagina
                })
                this.localItems = this.getItems
            }
            } catch (error) {
                console.log(error);
            };
        },
        // async selectItem() {
        //     try {
        //         // console.log('el item seleccionado es ',this.itemSelect)
        //         this.retornarItem(this.itemSelect)
        //     } catch (error) {
        //         console.log(error);
        //     }; }


    }
}

  </script>
  <style scoped>

  </style>
