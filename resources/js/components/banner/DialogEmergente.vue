<template>
    <div>

              <v-card>

                <v-card-title>
                  <span class="text-h5">Editar</span>
                </v-card-title>
                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                      >
                      {{bannerSelect.banner}}
                        <v-select
                          v-model="bannerSelect.banner"
                          :items="bannerSelect.programames"
                          item-value="id"
                          item-text="titulo"
                          label="Ciudad"
                          @change="setEstacion(bannerSelect.banner)"
                        ></v-select>
                      </v-col>
                      <v-col
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
                                          `images/programacion/${bannerSelect.banner.programa_banner}`
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
                                                  <input
                                                  :v-model="my_photo"
                                                  class="btn btn-primary"
                                                  value="Actualizar Imagen"
                                                  type="file"
                                                  @change="onFileInput($event)">
                                                  >
                                              </v-overlay>
                                              </v-fade-transition>
                                          </div>
                                </v-img>
                                  </v-card>
                                </v-hover>
                            </v-col>
                            <v-col cols="6">
                              <h2>{{bannerSelect.banner.programa_titulo}}</h2>
                              <p>Este es el texto</p>
                            </v-col>
                          </v-row>
                      </v-col>
                      <v-col cols="12"  class="">

                        <v-btn
                        fab
                        tile
                        small>
                        </v-btn>
                          <slot></slot>

                      </v-col>

                    </v-row>
                  </v-container>
                </v-card-text>
                </v-card>
    </div>
</template>
<script>
export default {
    name:'DialogEmergente',
    props: {
        bannerSelect: Object
    },
    data() {
        return {
            programa: this.bannerSelect.banner

        }
    },
    created() {
        this.inicio()
    },
    methods: {
        async inicio() {
            try {
                const res = await fetch('')
                const data = res.json()
                this.programasMes = data
                    console.log('Componente cargado con los programas del mes');
            } catch (error) {
                console.log(error);
            };

        },
        onFileInput(event) {
            const data = URL.createObjectURL(event.target.files[0]);
            this.my_photo = data;
        },
        setEstacion(id) {
            this.bannerSelect.banner = this.bannerSelect.programames.find(item => item.id == id)
        }

    }
}
</script>
