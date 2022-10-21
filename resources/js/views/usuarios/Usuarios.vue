<template>
    <div>
        <v-container>

            <DataTablesUsersVue/>
        </v-container>
    </div>
</template>

<script>
import DataTablesUsersVue from './DataTablesUsers.vue';
export default {
    name: 'UsuariosAdm',
    components: {
        DataTablesUsersVue
    },
    data() {
        return {

        }
    },

}
</script>

