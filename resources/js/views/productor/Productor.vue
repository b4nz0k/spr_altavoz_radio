<template>
    <div>
        <v-container>
            <dataTablesProductorVue/>
        </v-container>
    </div>
</template>

<script>
import dataTablesProductorVue from './dataTablesProductor';
export default {
    name: 'ProductorPage',
    components: {
        dataTablesProductorVue
    },
    data() {
        return {

        };
    },
}
</script>
