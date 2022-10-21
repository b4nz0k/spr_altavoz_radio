<template>
        <div class="text-center">
    <v-container>
      <v-row justify="center">
        <v-col cols="8">
          <v-container class="max-width">
            <v-pagination
            v-model="current_page"
            :length="lastPage"
            :total-visible="per_page"
            ></v-pagination>
          </v-container>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from 'vuex'
    export default {
        name:'PaginadorTablas',

        data() {
            return {

            }
        },
        watch: {
            current_page(NuevoValor, oldValor) {
                // console.log('NuevoValor', NuevoValor);

            }
        },
        computed: {
            ...mapState('cat', ['datos']),
            current_page: {
                get() {
                    return this.datos.current_page
                },
                set ( value ) {
                    console.log(value);
                    if (this.datos.buscar) {
                        return this.buscarTablas({
                            palabra: this.datos.buscar.palabra,
                            pagina: value
                        })
                    }
                    else {
                        return this.cargarTablas(value)
                    }
                }
            },
            lastPage: {
                get() {
                    return this.datos.last_page
                }
            },
            nextPage: {
                get() {
                    return this.datos.last_page
                }
            },
            per_page: {
                get() {
                    return this.datos.per_page
                }
            }
        },
        methods: {
            ...mapActions('cat', ['cargarTablas', 'buscarTablas'])
        }

    }

</script>
