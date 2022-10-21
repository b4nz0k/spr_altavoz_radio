<template>
<v-container>
    <v-row>
        <v-col cols="12" lg="12" xl="12" class="user-select">
            <v-chip color="primary" label text-color="white">
                <h2>Agregar Podcast</h2>
            </v-chip>
            <v-divider></v-divider>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" lg="12" xl="12">
            <podcast-crud></podcast-crud>
        </v-col>
    </v-row>
</v-container>
</template>

<script>
export default {
    props: [],
    components: {},
    data: () => ({}),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {},
    watch: {},
    methods: {},
}
</script>
