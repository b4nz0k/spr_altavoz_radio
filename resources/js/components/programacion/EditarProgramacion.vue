<template>
    <div>
        <v-card
        v-model="selectedEvent.id"
        @change="recargarDatos"
        >
            <v-toolbar dark
                color="primary"
                ><span class="text-h5">
                   Editar
                </span>
                <v-spacer></v-spacer>
                <v-btn @click="eliminar" icon>
                    <v-icon color="error" dark>mdi-delete</v-icon>
                </v-btn>
            </v-toolbar>
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
                          outlined
                          @change="setPrograma(programa)"
                          :rules="rules"
                        ></v-autocomplete>
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
        :nudge-right="40"
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
        ></v-time-picker>
      </v-menu>
    </v-col>
    <v-spacer></v-spacer>
    {{time2}}
    <v-col
      cols="11"
      sm="5"
    >
      <v-dialog
        ref="dialog"
        v-model="modal2"
        :return-value.sync="time2"
        persistent
        width="290px"
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
          ></v-text-field>
        </template>
        <v-time-picker
          v-if="modal2"
          v-model="time2"
          format="24hr"
          full-width
        >
          <v-spacer></v-spacer>
          <v-btn
            text
            color="primary"
            @click="modal2 = false"
          >
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="$refs.dialog.save(time2)"
          >
            OK
          </v-btn>
        </v-time-picker>
      </v-dialog>
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
         @click="cerrarEditar"
        >
         <v-icon dark> mdi-close </v-icon>
        </v-btn>

    </v-col>
    <v-col cols="6" class="shrink" style="min-width: 220px;">
        <v-text-field v-model="color" hide-details class="ma-0 pa-0" solo>
					<template v-slot:append>
						<v-menu v-model="menu" top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
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
                    <v-dialog v-model="dialog"
                    max-width="500px">
                    <v-card>

                        <v-toolbar dark
                        color="primary"
                        ><span class="text-h5" centered>
                            Confirmacion
                            <!-- ¿Estas Seguro que desea Eliminar este registro? -->
                        </span>
                    </v-toolbar>
                        <v-card-title class="text-h5">Estas seguro de borrar este registro?
                    </v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                            <v-btn color="info" @click="dialog = false">Cancelar</v-btn>
                            <v-btn color="error" @click="deleteItemConfirm">Si</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
                    </v-dialog>
        </v-card>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    name:'EditarProgramacion',
    props: {
        cerrarEditar: Function,
        estacionSelect: Number,
        // variables del componente padre
        selectedEvent: Object,
        cargarEventos: Function

    },
    data() {
        return {
            dialog: false,
            programa: null,
            programacion_mes: null,
            transmisionSelect: null,
            transmision: [
                'Transmision1',
                'Transmision2',
                'Transmision3',
                'Transmision4',
            ],
            menuFecha1: null,
            date1: this.selectedEvent.start.toString().slice(0, 10),
            fechaInicio: null,
            fechaFin: null,
            time: null,
            time2: null,
            menu2: false,
            modal2: false,
            color: '#1976D2FF',
		    mask: '!#XXXXXXXX',
		    menu: false,
            rules: [
            value => !!value || 'Requerido'
            ],
        }
    },
    mounted() {

    },
    created() {
        this.recargarDatos()
        this.inicio()
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
        recargarDatos() {
            // console.log('Desde el componente '+this.selectedEvent.id)
                if (this.selectedEvent.id) {
                    this.programa = this.selectedEvent.programa_id;
                    this.transmisionSelect = this.selectedEvent.tipo_transmision
                    this.fechaInicio = this.selectedEvent.start.toString().slice(0, 10)
                    this.fechaFin = this.selectedEvent.end.toString().slice(0, 10)
                    this.time = this.selectedEvent.hr_inicio
                    this.time2 = this.selectedEvent.hr_final
                    this.color = this.selectedEvent.color
                }
                else {
                    console.log(this.selectedEvent)
                    let start = (new Date(this.selectedEvent.start).toString().slice(15,21))
                    let end =   (new Date(this.selectedEvent.end).toString().slice(15,21))
                    // let end =   (new Date(this.selectedEvent.end).toISOString().slice(0, 10))
                    let fecha = (new Date(this.selectedEvent.start).toISOString().slice(0, 10))
                    this.fechaInicio = fecha + ' ' + start
                    this.fechaFin = fecha  + ' ' + end
                    this.time = start
                    this.time2 = end
                    this.color = this.selectedEvent.color
                    this.transmisionSelect = this.transmision[0]
                }
        },
        async inicio() {
            try {
              // console.log('Creando el componente dialog')
                if (this.selectedEvent.id) {
                    await axios.get('programacion/edit/'+this.selectedEvent.id).then(response => {
                    this.programacionSelect =  response.data
                    // console.log(response.data)
                });
            }
                await axios.get('programa/v-select').then(response => {
                    this.programacion_mes =  response.data
                    // console.log(response.data)
                });

                // console.log(this.programa)

            } catch (error) {
                console.log(error)
            }
        },
        setPrograma(item) {
            // console.log('programa cambiado')
            // console.log(item)
        },
        async guardar() {
            try {
              console.log('accediendo al save')
                let formData = new FormData();
                this.fechaInicio =  this.fechaInicio.substr(0, 10)+' '+this.time+':00'
                this.fechaFin = this.fechaInicio.substr(0, 10)+' '+this.time2+':00'

                formData.append('programa_id',Number(this.programa)) //int
                formData.append('tipo_transmision',this.transmisionSelect)//String
                formData.append('hora_inicio',this.fechaInicio) //date
                formData.append('hora_final',this.fechaFin) //date
                formData.append('color',this.color) //date
                if (this.selectedEvent.id)  {
                    formData.append('id',Number(this.selectedEvent.id)) //int
                    await axios.post('programacion/update', formData ).then(response => {
                        console.log('Accediendo al update ',response.data)
                        if (response.data.msg)
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                    }).catch(error => {
                        this.$store.dispatch('sanckbarsMessage', [error.response.data, 'error', true, '', ['top', 'right']]);
                    })
                }
                else {
                      console.log('guardando los datos ',formData)
                    await axios.post('programacion/add', formData).then(response => {
                      console.log('Accediendo al Store ',response.data)
                        if (response.data.msg)
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                    })
                }
                this.cerrarEditar()
                this.cargarEventos()
            } catch (error) {
                console.log(error);
            };
        },
        eliminar() {
            this.dialog = true
        },
        async deleteItemConfirm() {
            await axios.get('programacion/del/'+this.selectedEvent.id).then(response => {
                console.log(response.data)
                this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);

             })
             this.dialog = false
             this.cerrarEditar()
             this.cargarEventos()
        }
    }

}
</script>
