<template>
    <v-row class="fill-height">
<!--         <v-col cols="12">
            <p>createEvent:     {{  createEvent     }} </p>
            <p>createStart:     {{  createStart     }} </p>
        </v-col> -->
      <v-col>
        <v-sheet height="64">
          <v-toolbar flat >
          <v-btn @click="dialog=true" color="primary" dark>Agregar</v-btn>
            <v-btn
              outlined
              class="mr-4"
              color="grey darken-2"
              @click="setToday"
            >
              Hoy
            </v-btn>
            <v-btn
              fab
              text
              small
              color="grey darken-2"
              @click="prev"
            >

              <v-icon small>
                mdi-chevron-left
              </v-icon>
            </v-btn>

            <v-btn
              fab
              text
              small
              color="grey darken-2"
              @click="next"
            > <v-icon small>
                mdi-chevron-right
              </v-icon>
            </v-btn>
            <v-toolbar-title v-if="$refs.calendar">
              {{ $refs.calendar.title }}
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu
              bottom
              right
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  outlined
                  color="grey darken-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  <span>{{ typeToLabel[type] }}</span>
                  <v-icon right>
                    mdi-menu-down
                  </v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="type = 'day'">
                  <v-list-item-title>Dia</v-list-item-title>
                </v-list-item>
                <v-list-item @click="type = 'week'">
                  <v-list-item-title>Semana</v-list-item-title>
                </v-list-item>
<!--                 <v-list-item @click="type = 'month'">
                  <v-list-item-title>Month</v-list-item-title>
                </v-list-item>
                <v-list-item @click="type = '4day'">
                  <v-list-item-title>4 days</v-list-item-title>
                </v-list-item> -->
              </v-list>
            </v-menu>
          </v-toolbar>
        </v-sheet>
        <v-sheet height="600">
          <v-calendar
            ref="calendar"
            v-model="focus"
            color="primary"
            :short-weekdays="false"
            :type="type"
            :events="events"
            :event-color="getEventColor"
            :event-ripple="false"

            @click:more="viewDay"
            @click:date="viewDay"

            @mousedown:event="startDrag"
            @mousedown:time="startTime"
            @mousemove:time="mouseMove"
            @mouseup:time="endDrag"
            @mouseup:event="showEvent"

            @mouseleave.native="cancelDrag"
          >

          <template v-slot:event="{ event }">
            <div class="ms-2 flex">
                <h4>
                    {{ event.name}}
                </h4>
                <p>{{ event.hr_inicio }} - {{event.hr_final}} </p>
                <!-- {{new Date(event.start).toString().slice(15,21)}}
                {{new Date(event.end).toString().slice(15,21)}} -->

            </div>
      </template>
        </v-calendar>
        <!-- Model -->
          <v-dialog
            max-width="800px"
            v-model="dialog">
            <GuardarDialogVue
            :estacionSelect="estacionSelect"
            :cerrarDialog="cerrarDialog"
            :titulo="titulo"
            :cargarEventos="cargarEventos"
            />
          </v-dialog>
          <v-menu
            max-width="500px"
            v-model="selectedOpen"
            :close-on-content-click="false"
            :activator="selectedElement"
            offset-x
            >
                <EditarProgramacion
                v-if="selectedOpen"
                :selectedEvent="selectedEvent"
                :cerrarEditar="cerrarEditar"
                :cargarEventos="cargarEventos"
                />
          </v-menu>

 <!--          <v-menu
            v-model="selectedOpen"
            :close-on-content-click="false"
            :activator="selectedElement"
            offset-x
          >
            <v-card
              color="grey lighten-4"
              min-width="350px"
              flat
            >
              <v-toolbar
                :color="selectedEvent.color"
                dark
              >
                <v-btn icon>
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon>
                  <v-icon>mdi-heart</v-icon>
                </v-btn>
                <v-btn icon>
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </v-toolbar>
              <v-card-text>
                <span v-html="selectedEvent.details"></span>
              </v-card-text>
              <v-card-actions>
                <v-btn
                  text
                  color="secondary"
                  @click="selectedOpen = false"
                >
                  Cancel
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>
-->
        </v-sheet>
      </v-col>
    </v-row>
  </template>

  <script>
    import GuardarDialogVue from './GuardarDialog.vue'
    import EditarProgramacion from './EditarProgramacion.vue'
import { mapGetters } from 'vuex'
    export default {
        components: {
            GuardarDialogVue,
            EditarProgramacion

        },
      data: () => ({
        estacionSelect: 1,
        titulo: 'Sin Titulo',
        focus: '',
        type: 'week',
        typeToLabel: {
          month: 'Mensual',
          week: 'Semanal',
          day: 'Dia',
          '4day': '4 Dias',
        },
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Programa', 'Evento', 'Evento'],
        dialog: false,
        pagina: 1,
        // Draw
        dragEvent: null,
        dragStart: null,
        createEvent: null,
        createStart: null,
        extendOriginal: null,
      }),
      computed: {
        ...mapGetters(['infoUsuario'])

      },
      mounted () {
        this.$refs.calendar.checkChange()
      },
      created() {
        this.cargarEventos()
      },

      methods: {
        // Drag
        startDrag ({ event, timed }) {
            if (!this.selectedOpen) {
                if (event && timed) {
                    this.dragEvent = event
                    this.dragTime = null
                    this.extendOriginal = null
                }
            }
        },
        startTime (tms) {
            if (!this.selectedOpen) {
                const mouse = this.toTime(tms)
                if (this.dragEvent && this.dragTime === null) {
                    const start = this.dragEvent.start
                    this.dragTime = mouse - start
                } else {
                        this.createStart = this.roundTime(mouse)
                        this.createEvent = {
                        name:   `Evento #${this.events.length}`,
                        color:  this.rndElement(this.colors),
                        start:  this.createStart,
                        end:    this.createStart,
                        timed:  true,
                        estacion_radio_id:  this.infoUsuario.estacion_radio_id
                    }
                    this.events.push(this.createEvent)
                }
            }
        },
        mouseMove (tms) {
            if (!this.selectedOpen) {
                const mouse = this.toTime(tms)

                if (this.dragEvent && this.dragTime !== null) {
                    const start = this.dragEvent.start
                    const end = this.dragEvent.end
                    const duration = end - start
                    const newStartTime = mouse - this.dragTime
                    const newStart = this.roundTime(newStartTime)
                    const newEnd = newStart + duration

                    this.dragEvent.start = newStart
                    this.dragEvent.end = newEnd

                } else if (this.createEvent && this.createStart !== null) {
                    const mouseRounded = this.roundTime(mouse, false)
                    const min = Math.min(mouseRounded, this.createStart)
                    const max = Math.max(mouseRounded, this.createStart)

                    this.createEvent.start = min
                    this.createEvent.end = max
                }
                }
        },
        roundTime (time, down = true) {
          const roundTo = 60 // Eventos cada ciertos minutos
          const roundDownTime = roundTo * 60 * 1000

          return down
            ? time - time % roundDownTime
            : time + (roundDownTime - (time % roundDownTime))
        },
        toTime (tms) {
          return new Date(tms.year, tms.month - 1, tms.day, tms.hour, tms.minute).getTime()
        },
        endDrag () {
          this.dragTime = null
          this.dragEvent = null
          this.createEvent = null
          this.createStart = null
          this.extendOriginal = null
        //   this.dialog = true
        },
        cancelDrag () {
          if (this.createEvent) {
            if (this.extendOriginal) {
              this.createEvent.end = this.extendOriginal
            } else {
              const i = this.events.indexOf(this.createEvent)
              if (i !== -1) {
                this.events.splice(i, 1)
            }
        }
    }
          this.createEvent = null
          this.createStart = null
          this.dragTime = null
          this.dragEvent = null
        },
        rndElement (arr) {
          return arr[this.rnd(0, arr.length - 1)]
        },
        // End Drag

        async cargarEventos() {
            await axios.get('programacion/lista/'+this.pagina).then(res => {
                this.events = res.data.datos
                console.log(this.events)
            })
        },
        cerrarEditar() {
            this.selectedOpen = false
        },
        cerrarDialog() {
            this.dialog = false
        },
        viewDay ({ date }) {
          this.focus = date
          this.type = 'day'
        },
        getEventColor (event) {
          return event.color
        },
        setToday () {
          this.focus = ''
        },
        async prev () {
          this.$refs.calendar.prev()
          this.pagina = (this.pagina == 1) ? this.pagina - 2 : this.pagina -1
        //   console.log(this.pagina)
          await this.cargarEventos()
        },
        async next () {
          this.$refs.calendar.next()
          this.pagina = (this.pagina == 0) ? this.pagina + 2 : this.pagina +1
            // console.log(this.pagina)
          await this.cargarEventos()
        },
        showEvent ({ nativeEvent, event }) {
            if (event.id) {
                console.log('evento viejo', event)
                const open = () => {
                    this.selectedEvent = event
                    this.selectedElement = nativeEvent.target
                    requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
                    this.dialog = false
                }
                if (this.selectedOpen) {
                    this.selectedOpen = false
                    requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
                } else {
                    open()
                }
                nativeEvent.stopPropagation()
            }
            else if (!event.id) {
                console.log('evento nuevo', event)
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                if (this.selectedOpen) {
                    this.selectedOpen = false
                    // requestAnimationFrame(() => requestAnimationFrame(() => open()))
                    nativeEvent.stopPropagation()
                }
            }


        },
        updateRange ({ start, end }) {
          const min = new Date(`${start.date}T00:00:00`)
          const max = new Date(`${end.date}T23:59:59`)
        },
        rnd (a, b) {
          return Math.floor((b - a + 1) * Math.random()) + a
        },
      },
    }
  </script>
