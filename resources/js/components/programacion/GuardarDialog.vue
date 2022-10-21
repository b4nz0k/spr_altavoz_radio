<template>
    <div>
        <v-card>
            <v-toolbar dark
                color="primary"
                ><span class="text-h5">
                   {{titulo}}
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
    {{time}}
    <v-col
      cols="11"
      sm="5">

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
    <v-col cols="12">
        <v-btn
        @click="guardar" color="success" dark
        :disabled="disabledButton"
        >Guardar</v-btn>
        <v-btn
        fab dark small
         @click="cerrarDialog"
        >
         <v-icon dark> mdi-close </v-icon>
        </v-btn>
    </v-col>
  </v-row>

                  </v-container>
                </v-card-text>

        </v-card>
    </div>
</template>
<script>
import axios from 'axios'
export default {
    name:'GuardarDialog',
    props: {
        estacionSelect: Number,
        titulo: String,
        Evento: Object,
        cargarEventos: Function,
        cerrarDialog: Function,
    },
    data() {
        return {
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
            date1: new Date().toISOString().substr(0, 10),
            fechaInicio: null,
            fechaFin: null,
            time: null,
            time2: null,
            menu2: false,
            modal2: false,
            rules: [
            value => !!value || 'Requerido'
            ],
        }
    },
    created() {
        this.inicio()
    },
    computed: {
        disabledButton() {
            if (this.programa && this.transmisionSelect && this.time && this.time2 && this.fechaInicio ) {
                  return false;
            }
            else
                return true
        }

    },
    methods: {
        async inicio() {
            try {
                if (this.evento.programa_id != 0)
                    this
            await axios.get('programa/v-select').then(response => {
                this.programacion_mes =  response.data
                console.log(response.data)
              });


            } catch (error) {
                console.log(error)
            }
        },
        setPrograma(item) {
            console.log('programa cambiado')
            console.log(item)
        },
        async guardar() {
            try {
                let formData = new FormData();
                this.fechaInicio = this.fechaInicio.substr(0, 10)+' '+this.time+':00'
                this.fechaFin = this.fechaInicio.substr(0, 10)+' '+this.time2+':00'

                formData.append('estacion_radio',Number(this.estacionSelect)) //int
                formData.append('programa_id',Number(this.programa)) //int
                formData.append('tipo_transmision',this.transmisionSelect)//String
                formData.append('hora_inicio',this.fechaInicio) //date
                formData.append('hora_final',this.fechaFin) //date

                // console.log(this.fechaInicio)
                await axios.post('programacion/add', formData ).then(response => {
                    // console.log(response.data)
                    this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                })


            } catch (error) {
                console.log(error);
            };

            this.cerrarDialog()
            this.cargarEventos()
        }
    }

}
</script>
