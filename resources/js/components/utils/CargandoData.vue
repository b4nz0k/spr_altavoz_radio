<template v-slot:no-data>
         <v-data-table
            item-key="name"
            class="elevation-1"
            loading
            loading-text="Cargando... espere un momento"
          ></v-data-table>
</template>

<script>
export default {
    name: 'Nodata'
}
</script>
