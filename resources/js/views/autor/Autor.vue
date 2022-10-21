<template app>
    <div>
        <v-container>

            <DataTablesAutorVue/>
        </v-container>
    </div>
</template>

<script>
import DataTablesAutorVue from '../../components/autor/DataTableComponent.vue';
export default {
    name: 'AutorView',
    components: {
        DataTablesAutorVue
    },
    data() {
        return {

        }
    },

}
</script>
