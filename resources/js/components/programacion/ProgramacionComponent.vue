<template>
<v-container>
    <v-row class="fill-height mt-6" justify="center">
        <v-col cols="11">
            <v-sheet height="74">
                <v-toolbar flat>
                    <v-btn outlined class="mr-4" color="grey darken-2" @click="type = 'day'">Hoy</v-btn>
                    <v-btn fab text small color="grey darken-2" @click="prev">
                        <v-icon small>mdi-chevron-left</v-icon>
                    </v-btn>
                    <v-btn fab text small color="grey darken-2" @click="next">
                        <v-icon small>mdi-chevron-right</v-icon>
                    </v-btn>
                    <v-toolbar-title v-if="$refs.calendar">Programación de {{ $refs.calendar.title }}</v-toolbar-title>

                    <v-spacer></v-spacer>

                    <v-menu bottom right>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                                <span>{{ typeToLabel[type] }}</span>
                                <v-icon right>mdi-menu-down</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item @click="type = 'day'">
                                <v-list-item-title>Día</v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="type = 'week'">
                                <v-list-item-title>Semana</v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="type = 'month'">
                                <v-list-item-title>Mes</v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="type = '4day'">
                                <v-list-item-title>4 días</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-toolbar>
            </v-sheet>
            <v-sheet height="700">
                <transition appear appear-class="custom-appear-class" appear-to-class="custom-appear-to-class" (2.1.8+) appear-active-class="custom-appear-active-class">
                    <v-calendar ref="calendar" :key="registro_calendario" v-model="value" color="primary" type="day" :events="events" :event-color="getEventColor" :event-ripple="false" @change="getEvents" @mousedown:event="startDrag" @mousedown:time="startTime" @mousemove:time="mouseMove" @mouseup:time="endDrag" @mouseleave.native="cancelDrag" @click:event="showEvent" @click:date="viewDay">
                        <template v-slot:event="{ event, timed, eventSummary }">
                            <span class="v-event-draggable" v-if="event.id==0">Este evento no ha sido registrado</span>
                            <div class="v-event-draggable" v-html="eventSummary()"></div>
                            <div v-if="timed" class="v-event-drag-bottom" @mousedown.stop="extendBottom(event)"></div>
                        </template>

                        <template #day-body="{ date, week }">
                            <div class="v-current-time" :class="{ first: date === week[0].date }" :style="{ top: nowY }"></div>
                        </template>
                    </v-calendar>
                </transition>
                <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                    <v-card color="grey lighten-4" max-width="350px" flat>
                        <v-toolbar :color="selectedEvent.color" dark>
                            <v-btn icon>
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn icon>
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                            <v-btn icon>
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <span v-html="selectedEvent"></span>
                            <br />
                            <v-col cols="12">
                                <v-text-field label="Programa" placeholder="Programa..." outlined v-model="n_programa"></v-text-field>
                            </v-col>
                            <v-row>
                                <v-col cols="6">
                                    <v-text-field label="Hora de inicio" placeholder="Hora de inicio" outlined v-model="h_inicio" disabled></v-text-field>
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field label="Hora de final" placeholder="Hora final" outlined disabled v-model="h_final"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    {{vmodel_podcast}}
                                    <v-autocomplete v-model="vmodel_podcast" :items="podcastList" item-text="titulo" item-value="id" placeholder="Podcast" no-data-text="No hay resultados" solo dense outlined></v-autocomplete>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12">
                                    {{vmodel_programas}}
                                    <v-autocomplete v-model="vmodel_programas" :items="programasList" item-text="titulo" item-value="id" placeholder="Programas" no-data-text="No hay resultados" solo dense outlined></v-autocomplete>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn text color="secondary" @click="selectedOpen = false">Cancelar</v-btn>
                            <v-btn text color="secondary" @click="RegEvento()" v-if="vmodel_programas!=0 && vmodel_programas!=undefined">Registrar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-menu>
            </v-sheet>
        </v-col>
    </v-row>
</v-container>
</template>

<style lang="scss" scoped>
.v-current-time {
    height: 2px;
    background-color: #ea4335;
    position: absolute;
    left: -1px;
    right: 0;
    pointer-events: none;

    &.first::before {
        content: "";
        position: absolute;
        background-color: #ea4335;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-top: -5px;
        margin-left: -6.5px;
    }
}

.v-event-draggable {
    padding-left: 6px;
}

.v-event-timed {
    user-select: none;
    -webkit-user-select: none;
}

.v-event-drag-bottom {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 4px;
    height: 4px;
    cursor: ns-resize;

    &::after {
        display: none;
        position: absolute;
        left: 50%;
        height: 4px;
        border-top: 1px solid white;
        border-bottom: 1px solid white;
        width: 16px;
        margin-left: -8px;
        opacity: 0.8;
        content: "";
    }

    &:hover::after {
        display: block;
    }
}
</style>

<script>
export default {
    data: () => ({
        n_programa: "",
        h_inicio: "",
        h_final: "",
        vmodel_podcast: 0,
        vmodel_programas: 0,
        podcastList: [],
        programasList: [],
        selectedOpen: false,
        selectedEvent: {},
        selectedElement: null,
        value: "",
        registro_calendario: 0,
        type: "month",
        typeToLabel: {
            month: "Mes",
            week: "Semana",
            day: "Día",
            "4day": "4 días"
        },

        valueLine: "",
        ready: false,

        events: [],
        colors: [
            "#2196F3",
            "#3F51B5",
            "#673AB7",
            "#00BCD4",
            "#4CAF50",
            "#FF9800",
            "#757575"
        ],
        names: [],
        dragEvent: null,
        dragStart: null,
        createEvent: null,
        createStart: null,
        extendOriginal: null,
        vmodel_inicio: 0,
        vmodel_final: 0,
        vmodel_temp: 0
    }),
    methods: {
        showEvent({
            nativeEvent,
            event
        }) {
            const open = () => {
                this.selectedEvent = event;
                this.vmodel_inicio = event.start;
                this.vmodel_fin = event.end;
                this.vmodel_temp = event.id;
                this.vmodel_podcast = 0;
                this.vmodel_programas = 0;
                this.selectedElement = nativeEvent.target;

                setTimeout(() => (this.selectedOpen = true), 10);
                this.n_programa = event.name;
                this.h_inicio =
                    new Date(event.start).getHours() +
                    ":" +
                    new Date(event.start).getMinutes();
                this.h_final =
                    new Date(event.end).getHours() +
                    ":" +
                    new Date(event.end).getMinutes();
            };

            if (this.selectedOpen) {
                this.selectedOpen = true;
                setTimeout(open, 10);
            } else {
                open();
            }
            this.vmodel_podcast = event.id_podcast;
            console.log(event);
            this.vmodel_programas = event.id_programa;

            nativeEvent.stopPropagation();
        },
        startDrag({
            event,
            timed
        }) {
            if (event && timed) {
                this.dragEvent = event;
                this.dragTime = null;
                this.extendOriginal = null;
            }
        },
        startTime(tms) {
            const mouse = this.toTime(tms);

            if (this.dragEvent && this.dragTime === null) {
                const start = this.dragEvent.start;

                this.dragTime = mouse - start;
            } else {
                this.createStart = this.roundTime(mouse);
                this.createEvent = {
                    name: `Programa #${this.events.length}`,
                    color: this.rndElement(this.colors),
                    start: this.createStart,
                    end: this.createStart,
                    timed: true,
                    id: 0
                };

                this.events.push(this.createEvent);
            }
        },
        extendBottom(event) {
            this.createEvent = event;
            this.createStart = event.start;
            this.extendOriginal = event.end;
        },
        mouseMove(tms) {
            const mouse = this.toTime(tms);

            if (this.dragEvent && this.dragTime !== null) {
                const start = this.dragEvent.start;
                const end = this.dragEvent.end;
                const duration = end - start;
                const newStartTime = mouse - this.dragTime;
                const newStart = this.roundTime(newStartTime);
                const newEnd = newStart + duration;

                this.dragEvent.start = newStart;
                this.dragEvent.end = newEnd;
            } else if (this.createEvent && this.createStart !== null) {
                const mouseRounded = this.roundTime(mouse, false);
                const min = Math.min(mouseRounded, this.createStart);
                const max = Math.max(mouseRounded, this.createStart);

                this.createEvent.start = min;
                this.createEvent.end = max;
            }
        },
        endDrag() {

            this.dragTime = null;
            this.dragEvent = null;
            this.createEvent = null;
            this.createStart = null;
            this.extendOriginal = null;
        },
        cancelDrag() {
            if (this.createEvent) {
                if (this.extendOriginal) {
                    this.createEvent.end = this.extendOriginal;
                } else {
                    const i = this.events.indexOf(this.createEvent);
                    if (i !== -1) {
                        this.events.splice(i, 1);
                    }
                }
            }

            this.createEvent = null;
            this.createStart = null;
            this.dragTime = null;
            this.dragEvent = null;
        },
        roundTime(time, down = true) {
            const roundTo = 15; // minutes
            const roundDownTime = roundTo * 60 * 1000;

            return down ?
                time - (time % roundDownTime) :
                time + (roundDownTime - (time % roundDownTime));
        },
        toTime(tms) {
            return new Date(
                tms.year,
                tms.month - 1,
                tms.day,
                tms.hour,
                tms.minute
            ).getTime();
        },
        getEventColor(event) {
            const rgb = parseInt(event.color.substring(1), 16);
            const r = (rgb >> 16) & 0xff;
            const g = (rgb >> 8) & 0xff;
            const b = (rgb >> 0) & 0xff;

            return event === this.dragEvent ?
                `rgba(${r}, ${g}, ${b}, 0.7)` :
                event === this.createEvent ?
                `rgba(${r}, ${g}, ${b}, 0.7)` :
                event.color;
        },
        getEvents({
            start,
            end
        }) {
            const events = [];
            const min = new Date(`${start.date}T00:00:00`).getTime();
            const max = new Date(`${end.date}T23:59:59`).getTime();
            const days = (max - min) / 86400000;
            const eventCount = this.rnd(days, days + 20);

            events.push({
                name: this.rndElement(this.names),
                color: this.rndElement(this.colors),
                start,
                end,
                timed
            });

            this.events = events;
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a;
        },
        rndElement(arr) {
            return arr[this.rnd(0, arr.length - 1)];
        },
        getCurrentTime() {
            return this.cal ?
                this.cal.times.now.hour * 60 + this.cal.times.now.minute :
                0;
        },
        scrollToTime() {
            const time = this.getCurrentTime();
            const first = Math.max(0, time - (time % 30) - 30);
            this.cal.scrollToTime(first);
        },
        updateTime() {
            setInterval(() => this.cal.updateTimes(), 60 * 1000);
        },
        podcast() {
            axios.get("/podcast/list/all").then(response => {
                console.log(response.data);
                this.podcastList = response.data;
            });
        },
        programas() {
            axios.get("/programa/list/all").then(response => {
                this.programasList = response.data;
            });
        },
        RegEvento() {
            var datos = new FormData();
            datos.append("inicio", this.vmodel_inicio);
            datos.append("fin", this.vmodel_fin);
            datos.append("id_programa", this.vmodel_programas);

            datos.append("id_temp", this.vmodel_temp);

            axios.post("/programacion/insertar", datos).then(response => {

                //respuesta tras
            });
        },
        setToday() {
            this.focus = "";
        },
        viewDay({
            date
        }) {
            this.focus = date;
            this.type = "day";
        },
        prev() {
            this.$refs.calendar.prev();
        },
        next() {
            this.$refs.calendar.next();
        }
    },
    mounted() {

        this.scrollToTime();
        this.updateTime();
        this.podcast();
        this.programas();
    },
    computed: {
        cal() {
            return this.ready ? this.$refs.calendar : null;
        },
        nowY() {
            return this.cal ? this.cal.timeToY(this.cal.times.now) + "px" : "-10px";
        }
    }
};
</script>
