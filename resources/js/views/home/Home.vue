<template>
<v-container >
    <v-row>
        <v-col cols="12" xl="12">
            <v-card class="py-10 pl-10 pr-10">
                <h1 class="text-uppercase mb-4">Bienvenido</h1>
                Estación: <strong>{{infoUsuario.estacion}}</strong>
                <br>
                Hola <strong>{{ infoUsuario.name }}</strong>, bienvenido al Sistema Gestor de Contenidos de <strong>altavozradio.mx</strong>
            </v-card>
        </v-col>
    </v-row>
</v-container>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex";

export default {
    props: [],
    components: {},
    data: () => ({}),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {
        ...mapGetters(['infoUsuario']),
    },
    watch: {},
    methods: {},
}
</script>
