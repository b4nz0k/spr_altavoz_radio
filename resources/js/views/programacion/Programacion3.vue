<template>
    <div class="row">

      <div class="col-12"      >
          <v-hover v-slot="{ hover }">
        <v-card
        min-height="500"
>
              <v-card-title>

                  <v-expand-transition>
                      <div
                      v-if="hover"
                      class="d-flex transition-fast-in-fast-out  darken-1 text-h6"
                      style="width:100%;"
                      >
                        {{fechaSemana}}
                  </div>
                  </v-expand-transition>
<h4>{{dia}} </h4>


                <v-spacer></v-spacer>
              <!-- C {{cambioPagina}} -->
              <v-btn
                class="mx-2"
                fab
                dark
                small
                color="indigo"
                @click="agregar"
                >
                    <v-icon dark>
                        mdi-plus
                    </v-icon>
                </v-btn>
          </v-card-title>
          <v-card-text>

              <v-simple-table>
                  <template v-slot:default>
                  <thead>
                      <tr>
                          <th scope="col">Hr</th>
                          <th scope="col">Programa</th>
                      </tr>
                  </thead>
                      <draggable
                      class="list-group"
                      v-bind="dragOptions"
                      @start="drag = true"
                      @end="drag = false"
                      v-model="events" tag="tbody">
                      <tr v-for="(item, index) in events" :key="index">
                            <td :bgcolor="item.color"
                            link @click="editarPrograma(item.id)"
                            >{{item.hr_inicio.substr(0, 5)}}- {{item.hr_final.substr(0, 5)}}</td>
                            <td :bgcolor="item.color"
                            link @click="editarPrograma(item.id)"

                            >
                              <v-tooltip v-if="events.length > 0" right color="success">
                                      <template v-slot:activator="{ on, attrs }">
                                          <button  v-bind="attrs" v-on="on"

                                          >
                                              {{item.name}}
                                          </button>
                                      </template>
                                      <code>
                                          id: {{item.id }} <br/>
                                          Programa: {{item.name}} <br/>
                                          Empieza: {{item.hr_inicio.substr(0, 5)}} <br/>
                                          Termina: {{item.hr_final.substr(0, 5)}} <br/>
                                          Dia: {{item.dia }} {{item.start.substr(0,10)}} <br/>
                                          Transmision {{item.tipo_transmision}}
                                      </code>
                                  </v-tooltip>
                          </td>
                        </tr>
                      <tr v-if="events.length == 0" bgcolor="#FFCDD2">
                        <td>0 </td>
                        <td>
                            No hay Programas
                        </td>
                      </tr>
                      </draggable>
                  </template>
                </v-simple-table>
                <v-dialog
                  max-width="500px"
                  v-model="editarDialog"
                  :close-on-content-click="false"
                  offset-x
                  >
                  <EditarP
                  v-if="editarDialog"
                  :programaSelect="programaSelect"
                  :cerrarDialogEditar="cerrarDialogEditar"
                  :cargarEventos="cargarEventos"
                  />
                </v-dialog>
                <v-dialog
                max-width="500px"
                v-model="agregarDialog"
                :close-on-content-click="false"
                offset-x
                >
                <AgregarP
                v-if="agregarDialog"
                :cerrarDialogAgregar="cerrarDialogAgregar"
                :cargarEventos="cargarEventos"
                :fechaSemana="fechaSemana"
                    />
                  </v-dialog>
          </v-card-text>
        </v-card>
    </v-hover>

      </div>
      <rawDisplayer class="col-3" :value="events" title="List" />
    </div>
  </template>
  <script>
  import AgregarP from './Agregar.vue'
  import EditarP from './Editar.vue'
  import draggable from "vuedraggable";
  import rawDisplayer from '../../components/banner/raw-displayer.vue'
  import { mapGetters } from "vuex";
  import axios from 'axios';

  export default {
      name: 'BannerVista',
      display: "Table",
      order: 8,
      components: {
          draggable,
          rawDisplayer,
          EditarP,
          AgregarP,
      },
      props: {
        dia: String,
        pagina: Number,
        rango: Function,
      },
      data() {
          return {
              events: [],
              dragging: false,
              editarDialog : false,
              agregarDialog: false,
              programaSelect: [],
              fechaSemana: null,
              fechaSemanaOriginal: null,
              }
      },
      mounted() {
          this.cargarEventos()
      },
      computed: {
          ...mapGetters(['infoUsuario',]),
          dragOptions() {
              return {
                  animation: 200,
                  group: "description",
                  disabled: false,
                  ghostClass: "ghost",
              };
          },
          cambioPagina: {
            get() {
                return this.pagina
            },
            set() {
            //  console.log('cambio de pagina')
                return this.pagina
            }
          },
      },
      watch: {
        cambioPagina(nuevoValor, valorAnterior){
                // console.log("El nombre pasó de ser %s a %s", valorAnterior, nuevoValor);
                this.cargarEventos()
            }
        },
      methods: {
        async cargarEventos() {
            // cambiar por lunes
            await axios.get('programacion/lista/'+this.pagina)
                .then( res => {
                // AsignarRango
                // console.log(res.data)
                    if (res.data.rango) {
                        // console.log('El rango es ', res.data.rango )
                        this.rango(res.data.rango)
                    }
                    if (this.dia == 'Lun') {
                        this.events = res.data.datos.filter(item => item.dia == 'lunes')
                        this.fechaSemana = new Date(res.data.start)
                        this.asignarFecha(res.data.start, 2)
                        if (this.events.length < 1 ){

                            // console.log('No hat Programas en Lunes')
                        }
                    }
                    if (this.dia == 'Mar') {
                        this.events = res.data.datos.filter(item => item.dia == 'martes')
                        this.asignarFecha(res.data.start, 3)
                        if (this.events.length < 1 ){

                            // console.log('No hat Programas en Martes')
                        }
                    }

                    if (this.dia == 'Mier') {
                        this.events = res.data.datos.filter(item => item.dia == 'mi\u00e9rcoles')
                        this.asignarFecha(res.data.start, 4)
                        if (this.events.length < 1 ){
                            // console.log('No hat Programas en Miercoles')
                        }
                    }
                    if (this.dia == 'Juev') {
                        this.events = res.data.datos.filter(item => item.dia == 'jueves')
                        this.asignarFecha(res.data.start, 5)
                        if (this.events.length < 1 ){
                            // console.log('No hat Programas en Jueves')
                        }
                    }
                    if (this.dia == 'Vier') {
                        this.events = res.data.datos.filter(item => item.dia == 'viernes')
                        this.asignarFecha(res.data.start, 6)
                        if (this.events.length < 1 ){
                            // console.log('No hat Programas en Viernes')
                        }
                    }
                    // console.log(this.fechaSemana)

                // console.log(this.events)
            })
        },
        editarPrograma(id) {
            console.log('Estas editan el programa, ',id)
            this.programaSelect = id
            this.editarDialog = true
        },
        cerrarDialogEditar() {
            this.editarDialog = false
        },
        agregar() {
            this.agregarDialog = true
        },
        cerrarDialogAgregar() {
            this.agregarDialog = false
        },
        asignarFecha(fecha, dias) {
            let res = new Date(fecha);
            res.setDate(res.getDate() + dias);
            this.fechaSemanaOriginal= res;
            this.formatoFecha(res)
        },
        formatoFecha(fecha) {
            let date, month, year;

            date = fecha.getDate();
            month = fecha.getMonth() + 1;
            year = fecha.getFullYear();

            date = date
                .toString()
                .padStart(2, '0');

            month = month
                .toString()
                .padStart(2, '0');

            this.fechaSemana = `${year}-${month}-${date}`;

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
    min-height: 15px;
  }
  .list-group-item {
    cursor: move;
  }
  .list-group-item i {
    cursor: pointer;
  }
  </style>
