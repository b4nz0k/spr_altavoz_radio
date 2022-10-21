<template>
    <div>
        <v-container>
            <v-row>
        <v-col cols="12" lg="12" xl="12" class="user-select">
            <v-chip color="primary" label text-color="white">
                <h2>Perfil</h2>
            </v-chip>
            <v-divider></v-divider>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" lg="12" xl="12">

            <CardUsuario/>

        </v-col>
    </v-row>
        </v-container>
    </div>
</template>

<script>
    import CardUsuario from '../../components/usuarios/CardUsuario.vue';
export default {
    name: '',
    components: {
        CardUsuario
    },
    data() {
        return {};
    },

};
</script>

<style scoped>
* {

}
</style>
