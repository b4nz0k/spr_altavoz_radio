<template>
<v-container>
    <v-row>
        <v-col cols="12" lg="12" xl="12" class="user-select">
            <v-chip color="primary" label text-color="white">
                <h2>Categorías</h2>
            </v-chip>
            <v-divider></v-divider>
            <v-container>
                <dataTablesCategorias/>
            </v-container>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" lg="12" xl="12">
        </v-col>
    </v-row>
</v-container>
</template>

<script>
    import dataTablesCategorias from '../../components/categorias/Datatables'
export default {
    props: [],
    components: {
        dataTablesCategorias
    },
    data: () => ({}),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {},
    watch: {},
    methods: {},
}
</script>
