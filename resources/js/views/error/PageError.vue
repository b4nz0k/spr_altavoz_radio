<template>
<v-container>
    <v-row align="center" justify="center">
        <v-col cols="12" xl="12" justify="center" align="center">
            <v-img :src="appError" max-width="550"></v-img>
        </v-col>
    </v-row>
    <v-row align="center" justify="center">
        <v-col cols="12" xl="12" class="text-center">
            <h1 class="text-xl-h1">404</h1>
        </v-col>
    </v-row>
</v-container>
</template>

<script>
import AppConfig from 'Constants/AppConfig';

export default {
    props: [],
    components: {},
    data: () => ({
        appError: AppConfig.appLogoError,
    }),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {

    },
    watch: {},
    methods: {},
}
</script>
