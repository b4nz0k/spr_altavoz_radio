<template>
    <div>
        <!-- accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  -->
            <v-row>
                <v-col cols="12">
                    <v-file-input
                        v-model="archivoX"
                        color="green accent-4"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        label="Cargar documento"
                        placeholder="Selecciona tu Documento"
                        prepend-icon="mdi-microsoft-excel"
                        outlined
                        :rules="rules"
                        :show-size="1000"
                        @change="cambio"
                    >
                        <template v-slot:selection="{ text }">
                        <v-chip
                            color="green accent-4"
                            dark
                            label
                            small
                            >
                            {{ text }}
                        </v-chip>
                        </template>
                    </v-file-input>

                </v-col>
                <v-col>
                    <div
                    v-if="loading" class="text-center ">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            >
                        </v-progress-circular>
                        <span class="text-h6"
                        color="primary">
                            {{estado}}
                        </span>
                    </div>
                </v-col>
                <v-col v-if="!errores"
                cols="12"
                class="flex justify-center"
                >
                    <v-alert type="error">
                        Se encontraron errores en el documento
                    </v-alert>
                    <v-list-item v-for="(item, index) in reportes" :key="index">
                        <v-list-item-content
                        >
                            <v-list-item-title>Columna  {{index + 1}}
                            </v-list-item-title>
                            <v-list-item-subtitle v-for="(it, index) in item" :key="index">
                                <p
                                v-if="it != true">
                                    {{it}}
                                </p>
                                <p v-else>
                                </p>
                            </v-list-item-subtitle>
                            <v-divider>
                            </v-divider>
                        </v-list-item-content>
                    </v-list-item>
                </v-col>
             </v-row>
    </div>
</template>
<script>
    import readXlsFile from 'read-excel-file'
    export default {

        name: 'GestionArchivos',
        data() {
            return {
                rules: [
                    value => !value || value.size < 2000000 || 'El documento debe ser menor a 2 MB!',
                ],
                archivoX: [],
                archivoR: [],
                loading: false,
                estado: null,
                reportes:[],
                errores: false,


            }
        },
        computed: {
        },
        methods: {
            async cambio() {
                if (this.archivoX) { // Si se agrega un archivo, vamos a leerlo....
                    try {
                        this.loading = true
                        this.estado = 'Validando Datos'
                        // console.log('hay un archivo', this.archivoX)
                        // Transformamos el archivo  a JSON y lo guardamos en un arreglo
                        await readXlsFile(this.archivoX)
                                    .then((rows) => {
                                        this.archivoR = rows
                                    })
                        // Aqui vamos a  validar los datos
                        this.archivoR.forEach((item, index) => {
                            let reporte = []
                        // if (item[0] != null && item[1] != null && item[2] != null && item[3] != null && item[4] != null  )
                        //  validamos si hay algun error y lo guardamos
                            if (index > 1 ) {
                                reporte[0] = this.valProgramaId(item[0], index -1)
                                reporte[1] = this.valTransmision(item[1], index -1)
                                reporte[2] = this.valFecha(item[2], index  -1)
                                reporte[3] = this.valHoraInicio(item[3], index  -1)
                                reporte[4] = this.valHoraFinal(item[4], index  -1)
                                reporte[5] = this.fechaComparacion({ fechaInicio: item[3], fechaFinal: item[4]})
                                console.log(reporte)
                                this.reportes.push(reporte)
                            }

                        });
                        // generando reporte
                        this.estado = 'Generando Reporte'
                        console.log(this.archivoR)
                        console.log(this.reportes)
                        // this.loading = false
                    } catch (error) {
                        console.log(error);
                    }
                }
                else if(!this.archivoX) {
                    console.log('Se quito el archivo', this.archivoX)
                }
            },
            // Validaciones
            valProgramaId(item, index) {
                try {
                    if(Number.isInteger(item) || parseInt(item)) {
                        this.archivoR[index][0] = parseInt(item)
                        return true
                    }
                    else
                        return 'Error en la fila '+index+', en el campo Programa ';
                } catch (error) {
                    return 'Error en la fila '+index+', en el campo Programa '+error;
                }
            },
            valTransmision(item, index) {
                try {
                    if(typeof item === 'string' || item instanceof String)
                        return true
                    else
                        return 'Error en la fila '+index+', en el campo Transmision'
                } catch (error) {
                    return 'Error en la fila '+index+', en el campo Transmision '+error;
                }
            },
            valFecha(fecha, index) {
                try {
                    if(Date.parse(fecha))
                        return true
                    else
                        return 'Error en la fila '+index+', en el campo Fecha '
                } catch (error) {
                    return 'Error en la fila '+index+', en el campo Fecha '+error;
                }
            },
            valHoraInicio(hora, index) {
                try { //la hora debe estar en formado HH:MM
                    let res
                    if ( res = this.decimalAHora(hora))  {
                        return true
                    }
                    else
                        return 'Error en la fila '+index+', en el campo HoraInicio '
                } catch (error) {
                    return 'Error en la fila '+index+', en el campo HoraInicio '+error;
                }
            },
            valHoraFinal(hora, index) {
                try {
                    let res
                    if ( res = this.decimalAHora(hora))  {
                        return true
                    }
                    else
                        return 'Error en la fila '+index+', en el campo HoraFinal '
                } catch (error) {
                    return 'Error en la fila '+index+', en el campo HoraFinal '+error;
                }
            },
            decimalAHora(num) {
                let decimal = num * 24; //Sacamos el formato de excel que es de dias , lo pasamos a horas
                let horas = Math.floor(decimal), // Obtenemos la parte entera
                restoHoras = Math.floor(decimal % 1 * 100), // Obtenemos la parde decimal
                decimalMinutos = restoHoras * 60 / 100, // Obtenemos los minutos expresado en decimal
                minutos = Math.floor(decimalMinutos), // Obtenemos la parte entera
                restoMins = Math.floor(decimalMinutos % 1 * 100), // Obtenemos la parde decimal
                segundos = Math.floor(restoMins * 60 / 100); // Obtenemos los segundos expresado en entero
                return `${('00'+horas).slice(-2)}:${('00'+minutos).slice(-2)}:${('00'+segundos).slice(-2)}`;
            },
            fechaComparacion(fechas, index) {
                if (fechas.fechaInicio < fechas.fechaFinal)
                    return true
                else
                    return 'Error en la fila '+index+', La fecha de inicio no es menor a la fecha final ';
            }

            // end -- Validaciones
        },
    }

</script>
