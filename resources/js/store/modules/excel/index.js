import axios from "axios";

export const namespaced = true
const state = {
    loading: null,
    archivoX: null

}
const getters = {

}
const mutations = {

}
const actions = {
    // Validaciones

    valProgramaId(datos) {
        try {
            if(Number.isInteger(datos.item) || parseInt(datos.item)) {
                this.archivoR[index][0] = parseInt(datos.item)
                return true
            }
            else
                return false
        } catch (error) {
            return 'Error columna '+index + error;
        }
    },
    valTransmision(datos) {
        try {
            if(typeof datos.item === 'string' || datos.item instanceof String)
                return true
            else
                return false
        } catch (error) {
            return 'Error columna '+datos.index + error;
        }
    },
    valFecha(datos) {
        try {
            if(Date.parse(datos.fecha))
                return true
            else
                return false
        } catch (error) {
            return 'Error columna '+datos.index + error;
        }
    },
    valHoraInicio({dispatch},datos) {
        try { //la hora debe estar en formado HH:MM
            let res
            if ( res = this.decimalAHora(datos.hora))  {
                return true
            }
            else
                return false
        } catch (error) {
            return 'Error columna '+datos.index + error;
        }
    },
    valHoraFinal(datos) {
        try {
            let res
            if ( res = this.decimalAHora(datos.hora))  {
                return true
            }
            else
                return false
        } catch (error) {
            return 'Error columna '+datos.index + error;
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
    }


}

export default {
    namespaced,
    state,
    getters,
    mutations,
    actions
}
