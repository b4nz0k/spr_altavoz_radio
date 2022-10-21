<template>
    <div>
        <v-dialog
                v-model="dialog"
                max-width="35%"
              >

                <v-card>
                    <v-toolbar dark
                    color="primary"
                    ><span class="text-h5">
                        {{ formTitle }}
                    </span></v-toolbar>
                  <v-card-title>
                  </v-card-title>

                  <v-card-text>

                    <v-container>
                      <v-row>
                        <v-col
                          cols="12"
                          sm="6"
                          md="12"
                        >
                          <v-text-field
                            v-model="editedItem.categoria"
                            label="Nombre de Categoria"
                            :rules="rules"
                            outlined
                            clearable
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="12"
                        >
                        <v-textarea
                        placeholder="Descripcion de categoria..."
                            ref="myTextarea"
                            v-model="editedItem.descripcion"
                        />
                                                    <!-- <textarea-autosize
                            /> -->
                        </v-col>

                      </v-row>
                    </v-container>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="close" > Cancelar </v-btn>
                    <v-btn color="success" @click="guardar"> Guardar</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>

    </div>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
    export default {
        name: 'DialogOpcion',
        props: {
            close: Function,
            save: Function
        },
        data() {
            return {
                editedItem: {},
                rules: [
                    value => !!value || 'Requerido',
                    value => (value && value.length >= 3) || 'Minimo 3 caracteres',
                    value => (value && value.length <= 50) || 'Maximo 50 caracteres',
                    ],


            }
        },
        computed: {
            // ...mapState('cat',['dialogOpcion', 'titulo']),
            ...mapGetters('cat',['getDialogOpcion', 'getTitulo', 'getItem']),
            dialog: {
                get() {
                    if (this.getDialogOpcion)
                        this.recargaritem()
                    return this.getDialogOpcion

                },
                set() {
                    return this.cambiarDialogOpcion(false)
                }
            },

            formTitle: {
                get() {
                    return this.getTitulo
                },
                set() {
                    return this.cargarItem()
                }
            }
        },
        created() {
        },
        methods: {
            ...mapActions('cat', ['cambiarDialogOpcion', 'cambiarDialogDel', 'cargarItem']),
            recargaritem() {
                this.editedItem = this.getItem;
                // console.log('cargando item en Dialog Opcion',this.editedItem)
            },
            guardar() {
                // console.log('Guardando datos ',this.editedItem)
                this.cargarItem(this.editedItem)
                // console.log('nueva cat ', this.editedItem)
                this.save()
            }
        }

    }
</script>
