<template>
    <div>

              <v-card>

                <v-toolbar dark
                color="primary"
                ><span class="text-h5">
                  Agregar
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
<!--
    <v-col
      cols="6"
      sm="5"
    >
    {{fechaInicio}}
      <v-menu
        ref="menuFecha1"
        v-model="menuFecha1"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="fechaInicio"
            label="Fecha de inicio"
            prepend-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="fechaInicio"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menuFecha1 = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.menuFecha1.save(date)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
    </v-col> -->
    <!--     {{fechaFin}}
    <v-col
      cols="6"
      sm="5"
    >
      <v-menu
        ref="menuFecha2"
        v-model="menuFecha2"
        :close-on-content-click="false"
        :return-value.sync="date2"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="fechaFin"
            label="Fecha Final"
            prepend-icon="mdi-calendar"
            readonly
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="fechaFin"
          no-title
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="menuFecha2 = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.menuFecha2.save(date)"
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
    </v-col> -->
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
                                          :src=" my_photo == null ?
                                          `${programa.imagen_banner}`
                                          : my_photo
                                          "
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
                      <v-col cols="6" >
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
                        <v-btn
                        fab
                        ma-2
                        large
                        @click="cerrarDialog"
                        >
                            <v-icon dark>
                                mdi-close
                            </v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                    </v-col>
                    <v-col cols="6" >
                        <!-- var{{estado}} -->
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
    name:'AgregarBanner',
    props: {
        cerrarDialog: Function,
        cargarBanners: Function
    },
    data() {
        return {
            status: true,
            searchPrograma: '',
            programa: null,
            programacion_mes: null,
            my_photo: null,
            estado: true,
            // menuFecha1: false,
            // menuFecha2: false,

            // date: new Date().toISOString().substr(0, 7),
            // date2: new Date().toISOString().substr(0, 7),

            // fechaInicio: null,
            // fechaFin: null,


        }
    },
    created() {
        this.getProgramacion()
    },
    methods: {
        async getProgramacion() {
            try {
                await axios.get('api-banner-mes').then(response => {
                    // console.log(response.data)
                    this.programacion_mes = response.data
                    console.log('programacion-mes',this.programacion_mes)
                })
            } catch (error) {
                console.log(error);
            };


        },
        onFileInput(event) {
            const data = URL.createObjectURL(event.target.files[0]);
            this.my_photo = data;
        },
        setEstacion(id) {
            // console.log('cambiando programa...')
            this.programa = this.programacion_mes.find(item => item.id == id)
            console.log('cambio al programa', this.programa)
            // console.log(this.programa)
        },
        async guardarBanner() {
            try {
                // this.programa.fechaInicio = this.fechaInicio
                // this.programa.fechaFin = this.fechaFin
                /*                 if (this.titulo == 'Editar') {
                    this.programa.banner = id
                    const res = await axios.post('api-update-banner', this.programa).then(response => {
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                    })
                } */
                    this.programa.my_photo = this.my_photo
                    this.programa.estado = this.estado
                    this.programa.fechahoy  = new Date().toISOString().substr(0, 10)
                    await axios.post('api-create-banner', this.programa).then(response => {
                            console.log(response.data)
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                            this.cargarBanners()
                    })
                this.cerrarDialog()

            // console.log(this.programa.my_photo)
            } catch (error) {
                this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', false, '', ['top', 'right']]);
            };
        },
        // cambioEstado() {
        //     this.estado = !this.estado
        // }


    }
}
</script>
