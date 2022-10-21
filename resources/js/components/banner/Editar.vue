<template>
    <div>

              <v-card>

                <v-toolbar dark
                color="primary"
                ><span class="text-h5">
                  Editar
                </span></v-toolbar>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                      >
                      <!-- {{programa}} -->
                        <v-autocomplete
                          v-model="programa"
                          :items="programacion_mes"
                          item-value="id"
                          item-text="titulo"
                          label="Programas"
                          attach
                          @change="setEstacion(programa)"
                        />
<!--
                            <template slot="selection" slot-scope="data">
                                Item Seleccionado -
                                {{ data.item.titulo }} / {{ data.item.created_at }}
                            </template>
                            <template slot="item" slot-scope="data">
                               Item a seleccionar
                                {{ data.item.titulo }} - {{ data.item.created_at }}
                            </template> -->
                            <!-- Buscador del Select -->
<!--                                 <template v-slot:prepend-item>
                                <v-list-item>
                                    <v-list-item-content>
                                         @input="search"
                                    <v-text-field v-model="searchPrograma" placeholder="Search" ></v-text-field>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider class="mt-2"></v-divider>
                                </template> -->
                            <!-- Buscador del Select  -->
                      </v-col>

                      <v-col
                      v-if="programa"
                      cols="12">
                        <!-- Imagen -->
                          <v-row>
                            <v-col cols="6">
                                <v-hover v-slot="{ hover }">
                                  <v-card
                                    :elevation="hover ? 12 : 2"
                                    :class="{ 'on-hover': hover }"
                                  >
                                  <v-img
                                          max-height="250"
                                          max-width="350"
                                          :src="programa.imagen_banner"
                                          :lazy-src="`https://picsum.photos/10/6?image=2`"
                                          aspect-ratio="1"
                                          class="grey lighten-2"
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
                                          <div>
                                            <v-fade-transition>
                                              <v-overlay
                                                  v-if="hover"
                                                  absolute
                                                  color="#036358"
                                                >
<!--                                                   <input
                                                  :v-model="my_photo"
                                                  class="btn btn-primary"
                                                  value="Actualizar Imagen"
                                                  type="file"
                                                  @change="onFileInput($event)">
                                                  > -->
                                              </v-overlay>
                                              </v-fade-transition>
                                          </div>
                                </v-img>
                                  </v-card>
                                </v-hover>
                            </v-col>
                            <v-col cols="6">
                              <h2>{{programa.titulo}}</h2>
                              <p>{{programa.subtitulo}}</p>
                            </v-col>
                          </v-row>
                      </v-col>
                      <v-col cols="6"  class="">
                        <v-btn
                        fab
                        ma-2
                        color="success"
                        large
                        @click="guardarBanner"
                        >
                            <v-icon dark>
                                mdi-cloud-upload
                            </v-icon>
                        </v-btn>
                          <slot></slot>
                      </v-col>
                      <v-col cols="6" >
                        <!-- var {{estado}} -->
                          <v-switch
                                  v-model="estado"
                                  :color="(estado ? 'success' : 'error')"
                                  inset
                                  :label="'Estado: '+ (estado ? 'Publicado' : 'Oculto')"
                                  ></v-switch>

                        </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                </v-card>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    name:'EditarBanner',
    props: {
        bannerSelect: Object,
        cerrarDialog: Function,
        cargarBanners: Function,
    },
    data() {
        return {
            searchPrograma: '',
            estado: true,
            banner: null,
            programa: null,
            programacion_mes: null,
            my_photo: null,
            // menuFecha1: false,
            // menuFecha2: false,

            // date: new Date().toISOString().substr(0, 7),
            // date2: new Date().toISOString().substr(0, 7),

            // fechaInicio: null,
            // fechaFin: null,


        }
    },
    mounted() {

    },
    created() {
        this.getProgramacion()
        this.getBanner()
    },
    methods: {
        async getProgramacion() {
            try {
                await axios.get('api-banner-mes').then(response => {
                    // console.log(response.data)
                    this.programacion_mes = response.data
                    // console.log('programacion-mes',this.programacion_mes)
                })
            } catch (error) {
                console.log(error);
            };
        },
        async getBanner() {
            this.programa = {
                banner_id: this.bannerSelect.id,
                id: this.bannerSelect.programas_id,
                titulo: this.bannerSelect.programa_titulo,
                subtitulo: this.bannerSelect.programa_subtitulo,
                imagen_banner: this.bannerSelect.programa_banner,
            }
            if (this.bannerSelect.status == 1)
                this.estado = true
            else
                this.estado = false

            console.log(this.programa)

/*                 console.log('entrando al getBanner', this.selectBanner)
                await axios.get('api-edit-banner/'+this.selectBanner).then(response => {
                    this.banner = response.data
                    console.log('editando el banner')
                    console.log(response.data)
                }).catch (error=> {
                    console.log(error);
                }) */
        },
        onFileInput(event) {
            const data = URL.createObjectURL(event.target.files[0]);
            this.my_photo = data;
        },
        setEstacion(id) {
            // console.log('cambiando programa...')
            this.programa = this.programacion_mes.find(item => item.id == id)
            console.log('cambio al programa', this.programa.id)
            // console.log(this.selectBanner)

            // console.log(this.programa)
        },
         guardarBanner() {
            try {
                    this.programa.banner_id = this.bannerSelect.id
                    this.programa.estado = this.estado
                     axios.post('api-update-banner', this.programa)
                    .then( response => {
                            console.log(response.data)
                            this.cargarBanners()
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        }
                    );
                this.cerrarDialog()
            } catch (error) {
                this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', false, '', ['top', 'right']]);
            };
        },


    }
}
</script>

