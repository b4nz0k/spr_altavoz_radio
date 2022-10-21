<template>
    <div>
        <v-container>
            <estacionesTables/>

        </v-container>
    </div>
</template>
<script>
    import estacionesTables from '../../components/estaciones/DataTables.vue'
    export default {
        name: 'EstacionesAdm',
        components: {
            estacionesTables
        },
        methods: {

        }
    }
</script>
