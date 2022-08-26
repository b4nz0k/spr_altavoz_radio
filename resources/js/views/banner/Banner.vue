<template>
  <div class="row">
    <v-container>
    <div class="col-8">
      <h3>Banner crud</h3>
            <v-simple-table>
                <template v-slot:default>
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Enlace</th>
                        <th scope="col">Accion</th>
                    </tr>
                </thead>
                    <draggable v-model="listaBanner" tag="tbody">
                    <tr v-for="(item, index) in listaBanner" :key="index">
                        <td>{{ item.id }}</td>
                        <td>{{ item.programa_titulo }}</td>
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
                        </v-btn></td>
                    </tr>
                    </draggable>
                    <v-dialog
                        v-if="dialog == true"
                        v-model="dialog"
                        max-width="700px">
                            <DialogEmergenteVue
                                :bannerSelect="bannerSelect"
                            >
                            <!-- Dentro del componente (<slot>)  -->
                                <v-btn
                                fab
                                dark
                                small
                                @click="cerrarDialog"
                                >

                                    <v-icon dark>
                                        mdi-close
                                    </v-icon>
                                </v-btn>
                            </DialogEmergenteVue>


                    </v-dialog>
                    <!-- Ventana Emergente -->

                </template>
            </v-simple-table>
    </div>
    <rawDisplayer class="col-3" :value="listaBanner" title="List" />
        </v-container>
  </div>
</template>
<script>
import draggable from "vuedraggable";
import rawDisplayer from '../../components/banner/raw-displayer.vue'
import DialogEmergenteVue from "../../components/banner/DialogEmergente.vue";
import { stringify } from 'querystring';

export default {
    name: 'BannerVista',
    display: "Table",
    order: 8,
    components: {
        draggable,
        rawDisplayer,
        DialogEmergenteVue
    },
    data() {
        return {

            listaBanner: [],
            bannerSelect: null,
            dragging: false,
            dialog: false,
            }

    },
    mounted() {
        this.cargarBanners()
    },
    computed: {

    },
    methods: {
        async cargarBanners() {
            try {
                const res = await fetch('api-banner')
                const data = await res.json()
                this.listaBanner = data;
                console.log('Cargando banners')
                console.log(this.listaBanner)

            } catch (error) {
                console.log(error);
            };

        },
        async editarBanner(id) {
            try {
                const res = await fetch('api-edit-banner/'+id)
                const data =  await res.json()
                this.bannerSelect = data;
                    console.log('editando el id '+id)
                    console.log(this.bannerSelect)

                this.dialog = true;
            } catch (error) {
                console.log(error);
            };
        },
        cerrarDialog() {
            this.dialog = false;
        }

    }


}
</script>
