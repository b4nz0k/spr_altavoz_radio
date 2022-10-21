<template>

    <v-row>
        <v-col cols="12" lg="12" xl="12" class="user-select">
<!--        <v-chip >
                <span class="text-h5 me-10">Programación</span>
                <span class="text-h5">Programación</span>
            </v-chip>
            <v-divider></v-divider> -->
    <v-card>
    <v-tabs
      v-model="tab"
      color="primary" label text-color="white"
      centered
      icons-and-text
    >
      <v-tabs-slider></v-tabs-slider>

      <v-tab href="#tab-1">
        Calendario
        <v-icon>mdi-calendar</v-icon>
      </v-tab>
      <v-tab href="#tab-2">
        Calendario 2
        <v-icon>mdi-cal endar</v-icon>
      </v-tab>

<!--       <v-tab href="#tab-2">
        Cargar Documento
        <v-icon>mdi-microsoft-excel</v-icon>
      </v-tab> -->
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item
        v-for="i in 2"
        :key="i"
        :value="'tab-' + i"
      >
        <v-card flat>
          <v-card-text>
              <div v-if="tab =='tab-'+ 1">
                <v-row>
                    <v-col cols="11">
                        <v-card-actions>
                            <v-icon
                                large
                                @click="back"
                                >
                                mdi-chevron-left
                            </v-icon>
                                <v-btn
                                rounded
                                @click="back"
                                color="primary">
                                    <span>Anterior</span>
                                </v-btn>
                                <!-- Antes del componente {{pagina}} -->
                                <v-spacer></v-spacer>
                                <h2>
                                    {{rangoString}}
                                    <!-- || pagina {{pagina}} -->
                                </h2>
                                <v-spacer></v-spacer>
                                <v-btn
                                rounded
                                @click="next"
                                color="primary">
                                    <span>Siguiente</span>
                                </v-btn>
                            <v-icon
                                large
                                @click="next"
                            >
                                mdi-chevron-right
                            </v-icon>
                        </v-card-actions>
                    </v-col>
                    <v-col cols="2">
                        <Programacion3 dia="Lun" :rango="rango"  :pagina="pagina"
                         />
                    </v-col>
                    <v-col cols="2">
                        <Programacion3 dia="Mar" :rango="rango"  :pagina="pagina"
                         />
                    </v-col>
                    <v-col cols="2">
                        <Programacion3 dia="Mier" :rango="rango" :pagina="pagina"
                         />
                    </v-col>
                    <v-col cols="2">
                        <Programacion3 dia="Juev" :rango="rango" :pagina="pagina"
                         />
                    </v-col>
                    <v-col cols="2">
                        <Programacion3 dia="Vier" :rango="rango" :pagina="pagina"
                         />
                    </v-col>
                </v-row>
              </div>
              <programacion v-if="tab =='tab-'+ 2"/>

            <!-- <GestionArchivosVue v-if="tab == 'tab-'+2"/> -->

        </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </v-card>

<!-- Componente gestion de archivos -->

<!-- Componente programacin -->
<!-- <Programacion2/> -->

<v-dialog
                        v-if="dialog == true"
                        v-model="dialog"
                        max-width="800px">

                            <GuardarDialog
                                v-if="dialog == true"
                                :estacionSelect="estacionSelect"
                                :cerrarDialog="cerrarDialog"
                                :titulo="titulo"
                            >
                            <!-- Dentro del componente (<slot>)  -->
                                <v-btn
                                fab
                                dark
                                small
                                @click="cerrarDialog"
                                >
                                    <v-icon dark>
                                        mdi-close
                                    </v-icon>
                                </v-btn>
                            </GuardarDialog>
                    </v-dialog>
        </v-col>
    </v-row>
</template>

<script>
import GestionArchivosVue from '../../components/programacion/GestionArchivos.vue'
import GuardarDialog from '../../components/programacion/GuardarDialog.vue'
import ProgLunes from '../../components/programacion/dia/ProgLunes.vue'
import ProgMartes from '../../components/programacion/dia/ProgMartes.vue'
import ProgMiercoles from '../../components/programacion/dia/ProgMiercoles.vue'
import ProgJueves from '../../components/programacion/dia/ProgJueves.vue'
import ProgViernes from '../../components/programacion/dia/ProgViernes.vue'
import Programacion2 from './Programacion2.vue'
import Programacion3 from './Programacion3.vue'
export default {
    props:[],
    components:{
        ProgLunes,
        ProgMartes,
        ProgMiercoles,
        ProgJueves,
        ProgViernes,
        GuardarDialog,
        Programacion2,
        Programacion3,
        GestionArchivosVue
    },
    data() {
        return {
            tab: null,
            currentItem: 'tab-Web',
            items: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes' ],
            estacionSelect: 1,
            dialog: false,
            pagina: 1,
            rangoString: null,
            titulo: 'Sin titulo',
            listaEstaciones: [
                {id: 1, estacion: 'Altavoz Radio Web', descripcion: null},
                {id: 2, estacion: 'Altavoz Radio Coatzacoalcos 104.3', descripcion: null},
                {id: 3, estacion: 'Altavoz Radio Tapachula 101.1', descripcion: null},
                {id: 4, estacion: 'Altavoz Radio Mazatlán 103.5', descripcion: null},
                {id: 5, estacion: 'Altavoz Radio Colima 102.9', descripcion: null},
            ]
             }
    },
    created () {},
    mounted () {},
    computed: {},
    watch: {},
    methods: {
        iniciarRegistro() {
            this.titulo = 'Registrar'
            this.dialog = true
        },
        cerrarDialog() {
            this.dialog = false
        },
        pruebaEstacion() {
            // console.log(this.estacionSelect)
        },
        async back() {
            if (this.pagina == -1) {
                this.pagina -=2
            }
            else {
                this.pagina = (this.pagina == 1) ? this.pagina - 2 : this.pagina -1
            }
        },
        async next() {
            this.pagina = (this.pagina == 0) ? this.pagina + 2 : this.pagina +1
        },
        rango(value) {
            this.rangoString = value
            // console.log('rango asignado')

        }
    },
}
</script>
