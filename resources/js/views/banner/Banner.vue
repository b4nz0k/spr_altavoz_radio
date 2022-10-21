<template>
  <div class="row">
    <v-container>

    <div class="col-12">
      <h3>Banners</h3>
      <v-card>
            <v-card-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" outlined dark  @click="agregarBanner">Agregar Banner</v-btn>
        </v-card-title>
        <v-card-text>
            <v-simple-table>
                <template v-slot:default>
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Img</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Enlace</th>
                        <th scope="col">Accion</th>
                    </tr>
                </thead>
                    <draggable
                    class="list-group"
                    v-bind="dragOptions"
                    @start="drag = true"
                    @end="drag = false"
                    v-model="listaBanner" tag="tbody">
                    <tr v-for="(item, index) in listaBanner" :key="index">
                        <td>{{ item.id }}</td>
                            <td>

                                    <!-- {{ item.programa_banner }} -->
                                <v-row >
                                    <!-- {{item.programa_banner}} -->
                                    <v-img
                                    :src="item.programa_destacado"
                                    lazy-src="https://picsum.photos/id/11/100/60"
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

                            </td>
                        <td>
                            <v-chip class="ma-2" :color="item.status ==1 ? 'green': 'warning'" outlined>
                                {{ item.programa_titulo }}
                            </v-chip>
                        </td>
                        <td><a  class="text-decoration-none" :href="item.link">Enlace</a></td>
                        <td>
                            <v-btn class="btn btn-outline-warning mx-2"
                                fab
                                dark
                                small
                                color="cyan"
                                @click="editarBanner(item.id)">
                                <v-icon dark>
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                            <v-btn class="btn btn-outline-warning mx-2"
                                fab
                                dark
                                small
                                color="error"
                                @click="eliminarBanner(item.id)">
                                <v-icon dark>
                                    mdi-window-close
                                </v-icon>
                            </v-btn>



                    </td>
                    </tr>
                    </draggable>
                    <v-dialog
                        v-model="dialogAgregar"
                        max-width="700px">
                            <AgregarBanner
                                :cargarBanners="cargarBanners"
                                :cerrarDialog="cerrarDialog"
                            />
                    </v-dialog>
                    <!-- Ventana Emergente -->
                    <v-dialog
                        v-model="dialogEditar"
                        max-width="700px"
                    >
                            <EditarBanner v-if="dialogEditar"
                            :cargarBanners="cargarBanners"
                            :bannerSelect="bannerSelect"
                            :cerrarDialog="cerrarDialog"
                        />
                    </v-dialog>
<!--                     <v-card>
                        <v-toolbar dark color="primary">
                            <span class="text-h5">
                            Agregar
                            </span>
                        </v-toolbar>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-autocomplete
                                        v-model="programa"
                                        :items="programacion_mes"
                                        item-value="id"
                                        item-text="titulo"
                                        label="Programas"
                                        attach
                                        @change="setEstacion(programa)"
                                        >
                                            <template slot="selection" slot-scope="data">
                                                {{ data.item.titulo }} / {{ data.item.created_at }}
                                            </template>
                                            <template slot="item" slot-scope="data">
                                                {{ data.item.titulo }} - {{ data.item.created_at }}
                                            </template>
                                    </v-autocomplete>
                                </v-col>
                                <v-col cols="12">

                                    <v-btn
                                    @click="cerrarDialogAgregar">
                                    cerrar
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    </v-card>

                    </v-dialog> -->

                </template>

            </v-simple-table>
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
                <v-btn v-if="infoUsuario.nivel > 2"  color="error" @click="deleteItemConfirm">Si</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
        </v-dialog>

        </v-card-text>
      </v-card>

    </div>
    <!-- <rawDisplayer class="col-3" :value="listaBanner" title="List" /> -->
    <v-btn
    dark
    large
    color="info"
    @click="guardarOrden">
        Guardar orden
    </v-btn>
        </v-container>


  </div>
</template>
<script>
import draggable from "vuedraggable";
import rawDisplayer from '../../components/banner/raw-displayer.vue'
import AgregarBanner from "../../components/banner/Agregar.vue";
import EditarBanner from "../../components/banner/Editar.vue";
import { mapGetters } from "vuex";
import axios from 'axios';

export default {
    name: 'BannerVista',
    display: "Table",
    order: 8,
    components: {
        draggable,
        rawDisplayer,
        AgregarBanner,
        EditarBanner,
    },
    data() {
        return {

            listaBanner: [],
            bannerSelect: null,
            dragging: false,
            dialogEditar: false,
            dialogAgregar: false,
            // Agregar Programa.
            programa: null,
            programacion_mes: null,
            dialogDelete: false,
            }

    },
    mounted() {
        this.cargarBanners()
    },
    computed: {
        ...mapGetters(['infoUsuario',]),
        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            };
        },
    },
    methods: {
        async cargarBanners() {
            try {
                const res = await fetch('api-banner')
                const data = await res.json()
                this.listaBanner = data;
                // console.log('Cargando banners')
                // console.log(this.listaBanner)

            } catch (error) {
                console.log(error);
            };

        },
        async editarBanner(id) {
                const res = await fetch('api-edit-banner/'+id)
                const data = await res.json()
                this.bannerSelect = data;
                //  this.bannerSelect =  id;
                console.log(this.bannerSelect)
                this.dialogEditar = true;
        },

        agregarBanner() {

            this.dialogAgregar = true
            // console.log('agregando banner')
        },

        cerrarDialogAgregar() {
            this.dialogAgregar = false
        },
        eliminarBanner(id) {
            // console.log(id)
            this.bannerSelect = id
            console.log('eliminando banner', this.bannerSelect)
            this.dialogDelete = true
        },


        // Funcional -------------------------------------------------------------------------
        async deleteItemConfirm() {
            try {
                const res = await fetch('api-delete-banner/'+this.bannerSelect)
                const data = await res.json()
                    if(data.answer)
                        // console.log('correcto')
                        this.$store.dispatch('sanckbarsMessage', [data.msg, 'success', true, '', ['top', 'right']]);
                        this.dialogDelete = false
                        this.cargarBanners()
            } catch (error) {
                this.$store.dispatch('sanckbarsMessage', [data.msg, 'error', true, '', ['top', 'right']]);
            }
        },
        cerrarDialog() {
            this.dialogAgregar = false;
            this.dialogEditar = false;
        },
        closeDelete() {
            this.dialogDelete = false
        },
        async guardarOrden() {
            try {
                await axios.post('api-orden-banner', this.listaBanner).then(response => {
                    // console.log(response.data)
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);

                })

            } catch (error) {
                this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', ['top', 'right']]);
            };
        },
    }


}
</script>
<style scoped>
    .button {
  margin-top: 35px;
}
.flip-list-move {
  transition: transform 0.5s;
}
.no-move {
  transition: transform 0s;
}
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}
.list-group {
  min-height: 20px;
}
.list-group-item {
  cursor: move;
}
.list-group-item i {
  cursor: pointer;
}
</style>
