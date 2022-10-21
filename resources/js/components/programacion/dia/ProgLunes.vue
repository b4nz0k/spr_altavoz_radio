<template>
    <div class="row">
      <v-container>

      <div class="col-12">
              <v-simple-table>
                  <template v-slot:default>
                  <thead>
                      <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Programa</th>
                          <th scope="col">Hora</th>
                          <th scope="col">Acciones </th>
                      </tr>
                  </thead>
                  <!-- ocnst -->
                      <draggable v-model="listaProgramacion" tag="tbody">
                      <tr v-for="(item, index) in listaProgramacion" :key="index">
                          <td>{{ item.id }}</td>
                          <td>{{ item.programa_titulo }}</td>
                          <td>{{ item.hr_inicio + ' / ' + item.hr_final}}</td>
                          <td>
                              <v-btn class="btn btn-outline-warning mx-2"
                                  fab
                                  dark
                                  small
                                  color="cyan"
                                  @click="editarProgramacion(item.id)">
                                  <v-icon dark>
                                      mdi-pencil
                                  </v-icon>
                              </v-btn>
    <!--                           <v-btn class="btn btn-outline-warning mx-2"
                                  fab
                                  dark
                                  small
                                  color="error"
                                  @click="eliminarProgramacion(item.id)">
                                  <v-icon dark>
                                      mdi-window-close
                                  </v-icon>
                              </v-btn> -->



                      </td>
                      </tr>
                      </draggable>
                    <v-dialog
                        v-if="dialog2 == true"
                        v-model="dialog2"
                        max-width="800px">
                            <EditarProgramacionVue
                                :cerrarDialog2="cerrarDialog2"
                                :titulo="titulo"
                                :programacionSelect="programacionSelect"
                            >
                            <!-- Dentro del componente (<slot>)  -->
                            </EditarProgramacionVue>


                    </v-dialog>


                  </template>

              </v-simple-table>
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

  import rawDisplayer from '../../banner/raw-displayer.vue'
  import { stringify } from 'querystring';
  import { mapGetters } from "vuex";
  import axios from 'axios';
import EditarProgramacionVue from '../EditarProgramacion.vue';

  export default {
      name: 'BannerVista',
      display: "Table",
      order: 8,
      components: {
          draggable,
          rawDisplayer,
          EditarProgramacionVue
      },
      data() {
          return {

              listaProgramacion: [],
              programacionSelect: null,
              dragging: false,
              dialog: false,
              dialog2: false,
              dialogAgregar: false,
              titulo: null,
              // Agregar Programa.
              programa: null,
              programacion_mes: null,
              }

      },
      mounted() {
          this.cargarBanners()
      },
      computed: {
          ...mapGetters(['infoUsuario',]),

      },
      methods: {
          async cargarBanners() {
              try {
                  const res = await fetch('programacion/dia/lunes')
                  const data = await res.json()
                  this.listaProgramacion = data;
                  // console.-log('Cargando banners')
                  // console.log(this.listaBanner)

              } catch (error) {
                  console.log(error);
              };

          },
          async editarProgramacion(id) {
              try {
                  this.titulo = 'Editar'
                  const res = await fetch('programacion/edit/'+id)
                  const data =  await res.json()
                  this.programacionSelect = data;
                  console.log('editando el id '+id)
                  console.log(this.programacionSelect)
                  this.dialog2 = true;
              } catch (error) {
                  console.log(error);
              };
          },
          async eliminarProgramacion(id) {
              try {
                  const res = await fetch('programacion-delete/'+id)
                  const data = await res.json()
                      if(data.answer)
                          console.log('correcto')
              } catch (error) {
                  console.log(error)
              }
          },
          cerrarDialog() {
              this.dialog = false;
          },
          cerrarDialog2() {
              this.dialog2 = false;
          },
          async agregarBanner() {
              const res = await fetch('api-edit-banner/'+1)
              const data =  await res.json()
              this.bannerSelect = data;
              this.titulo = 'Agregar'
              this.dialog = true

              // console.log('agregando banner')
          },
          cerrarDialogAgregar() {
              this.dialogAgregar = false
          },
          async guardarOrden() {
              try {
                  await axios.post('api-orden-banner', this.listaBanner).then(response => {
                      console.log(response.data)
                  })

              } catch (error) {
                  console.log(error);
              };
          },
      }


  }
  </script>
