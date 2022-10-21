<template>
    <div>
        <v-card>
            <v-toolbar dark
            color="primary"
            ><span class="text-h5">
                Editar
            </span>
            <v-spacer></v-spacer>
            <v-btn @click="eliminar" icon>
                <v-icon color="error" dark>mdi-delete</v-icon>
            </v-btn>
            <v-btn @click="cerrarDialogEditar" icon>
                <v-icon dark>mdi-close</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card-title>
            id {{programa}}
        </v-card-title>
        <v-card-text>
                  <v-container>
                    <v-row>
                        <v-col cols="12" >
                      <!-- {{programa}} -->
                        <v-autocomplete
                          v-model="programa"
                          :items="programacion_mes"
                          item-value="id"
                          item-text="titulo"
                          label="Programas"
                          outlined
                          :rules="rules"
                          ></v-autocomplete>

                          <!-- @change="setPrograma(programa)" -->
                            </v-col>
                        <v-col cols="6">
                            <v-autocomplete
                            v-model="transmisionSelect"
                            :items="transmision"
                            outlined
                            label="Transmision"
                            :rules="rules"
                            >
                            </v-autocomplete>
                        </v-col>
                        <v-col
                            cols="6"
                            sm="5"
                            >
                            <v-menu
                                ref="menuFecha1"
                                v-model="menuFecha1"
                                :close-on-content-click="false"
                                :return-value.sync="date1"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="auto"
                            >

                                <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="fechaInicio"
                                    label="Dia Programado"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    :rules="rules"
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
                                    @click="$refs.menuFecha1.save(date1)"
                                >
                                    OK
                            </v-btn>
                            </v-date-picker>
                        </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>

                        <v-col
                        cols="11"
                        sm="5"
                        >
                        <v-menu
                            ref="menu"
                            v-model="menu2"
                            :close-on-content-click="false"
                            :return-value.sync="time"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="time"
                                label="Hora de apertura"
                                prepend-icon="mdi-clock-time-four-outline"
                                readonly
                                outlined
                                v-bind="attrs"
                                v-on="on"
                                :rules="rules"
                            ></v-text-field>
                            </template>
                            <v-time-picker
                            v-if="menu2"
                            v-model="time"
                            full-width
                            format="24hr"

                            @click:minute="$refs.menu.save(time)"
                            >
                        </v-time-picker>
                        </v-menu>
                            </v-col>
        <v-spacer></v-spacer>
        <!-- {{time2}} -->
                        <v-col
                        cols="11"
                        sm="5"
                        >
                        <v-menu
                            ref="dialog"
                            v-model="menu3"
                            :close-on-content-click="false"
                            :return-value.sync="time2"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="time2"
                                label="Hora de cierre"
                                prepend-icon="mdi-clock-time-four-outline"
                                readonly
                                outlined
                                v-bind="attrs"
                                v-on="on"
                                :rules="rules"
                            ></v-text-field>
                            </template>
                            <v-time-picker
                            v-if="menu3"
                            v-model="time2"
                            full-width
                            format="24hr"
                            @click:minute="$refs.dialog.save(time2)">

                            </v-time-picker>
                        </v-menu>
                    </v-col>

                    </v-row>
                    <v-row>
                        <v-col cols="6">
                        <v-btn
                            @click="guardar" color="success" dark
                                    :disabled="disabledButton"
                                    >Guardar</v-btn>
                                    <v-btn
                                       fab dark small
                                        @click="cerrarDialogEditar"
                                >
                                <v-icon dark> mdi-close </v-icon>
                            </v-btn>
                        </v-col>
                        <v-col cols="6" class="shrink" style="min-width: 220px;">
                            <v-text-field v-model="color" hide-details class="ma-0 pa-0" solo>
                                        <template v-slot:append>
                                            <v-menu v-model="menuColores" top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
                                                <template v-slot:activator="{ on }">
                                                    <div :style="swatchStyle" v-on="on"></div>
                                                </template>
                                                <v-card>
                                                    <v-card-text class="pa-0">
                                                        <v-color-picker v-model="color" flat />
                                                    </v-card-text>
                                                </v-card>
                                            </v-menu>
                                        </template>
                            </v-text-field>
                        </v-col>
                    </v-row>

                  </v-container>
                </v-card-text>
        </v-card>


        <!-- Dialog Eliminar -->
        <v-dialog
        :close-on-content-click="false"
        v-model="dialogDelete" max-width="500px">
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
                    <v-btn  color="error" @click="deleteItemConfirm">Si</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>

    </div>
</template>
<script>
export default {
    name:'EditarProgrmacion',
    props: {
        programaSelect:Number,
        cerrarDialogEditar: Function,
        cargarEventos: Function,
    },
    data() {
        return {
            itemSelect: null,

            programa: null,
            programacion_mes: [],

            dialogDelete: false,
            menuFecha1: null,
            date1: null,
            fechaInicio: null,
            fechaFin: null,
            time: null,
            time2: null,
            menuColores: false,
            menu: false,
            menu2: false,
            menu3: false,
            color: '#1976D2FF',

            transmisionSelect: null,
            transmision: [
                'Transmision1',
                'Transmision2',
                'Transmision3',
                'Transmision4',
            ],

		    mask: '!#XXXXXXXX',
		    menu: false,
            rules: [
            value => !!value || 'Requerido'
            ],

        }
    },
    mounted() {
        this.cargarProgramas()
        this.cargarEvento()

    },
    computed: {
        disabledButton() {
            if (this.programa && this.transmisionSelect && this.time && this.time2 && this.fechaInicio ) {
                  return false;
            }
            else
                return true
        },
        swatchStyle() {
            const { color, menu } = this
            return {
                backgroundColor: color,
                cursor: 'pointer',
                height: '30px',
                width: '30px',
                borderRadius: menu ? '50%' : '4px',
                transition: 'border-radius 200ms ease-in-out'
            }
        }
    },
    methods: {
        async cargarProgramas() {
            try {
                await axios.get('programa/v-select')
                    .then(response => {
                        this.programacion_mes =  response.data
                        // console.log(response.data)
                })
            } catch (error) {
                console.log(error);
            };

        },
        async cargarEvento() {
            try {
                await axios.get('programacion/edit/'+this.programaSelect)
                    .then(response => {
                        this.itemSelect =  response.data
                        // console.log('los datos cargados son', this.itemSelect)
                        this.programa = response.data.programa_id;
                        this.transmisionSelect = response.data.tipo_transmision
                        this.fechaInicio = response.data.hora_inicio.toString().slice(0, 10)
                        this.fechaFin = response.data.hora_inicio.toString().slice(0, 10)
                        this.time = response.data.hr_inicio
                        this.transmisionSelect = response.data.tipo_transmision
                        this.time2 = response.data.hr_final
                        this.color = response.data.color
                        if (this.color.length < 1) {
                            this.color = '#2B47FFFF'
                        }
                    })
            } catch (error) {
                console.log(error);
            };
        },
        eliminar() {
            this.dialogDelete = true

        },
        closeDelete(){
            this.dialogDelete = false
        },
        async deleteItemConfirm() {
            try {
                // console.log('Eliminando', this.programaSelect)
                await axios.get('programacion/del/'+this.programaSelect)
                    .then(response => {
                        if (response.data.msg) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        }
                        else {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', ['top', 'right']]);
                        }
                    })
                this.dialogDelete = false
                this.cerrarDialogEditar()
                this.cargarEventos()
            } catch (error) {
                console.log(error);
            };

        },
        async guardar() {
                // console.log('accediendo al save')
                let formData = new FormData();

                this.fechaInicio =  this.fechaInicio.substr(0, 10)+' '+this.time
                this.fechaFin = this.fechaInicio.substr(0, 10)+' '+this.time2
                console.log('fecha inicio', this.fechaInicio)
                console.log('fecha final', this.fechaFin)
                formData.append('programa_id',Number(this.programa)) //int
                formData.append('tipo_transmision',this.transmisionSelect)//String
                formData.append('hora_inicio',this.fechaInicio) //date
                formData.append('hora_final',this.fechaFin) //date
                formData.append('color',this.color) //date
                if (this.programaSelect)  {
                    formData.append('id',Number(this.programaSelect)) //int
                    // console.log('datos antes de guardar',formData )
                    await axios.post('programacion/update', formData ).then(response => {
                        console.log('Accediendo al update ',response.data)
                        if (response.data.msg) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        }
                    }).catch(error => {
                        this.$store.dispatch('sanckbarsMessage', [error.response.data, 'error', true, '', ['top', 'right']]);
                    })
                }
                this.cerrarDialogEditar()
                this.cargarEventos()
        },
    }
}
</script>
