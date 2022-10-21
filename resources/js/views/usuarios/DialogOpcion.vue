<template>
    <div>
        <v-dialog
            v-model="dialog"
            max-width="600px"
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
                        outlined
                        clearable
                        v-model="editedItem.name"
                        label="Nombre"
                        :rules="rules"
                      ></v-text-field>
                    </v-col>
                    <v-col
                      cols="12"
                      sm="6"
                      md="12"
                    >
                      <v-text-field
                        outlined
                        clearable
                        v-model="editedItem.email"
                        label="Email"
                        align-content-end
                        :rules="rules"
                        suffix="@spr.gob.mx"

                      ></v-text-field>
                    </v-col>
                    <v-col
                      cols="6"
                      sm="6"
                      md="6"
                    >
                      <v-text-field
                        outlined
                        clearable
                        v-model="editedItem.password"
                        label="Password"
                        type="password"
                        :rules="rules"
                      ></v-text-field>
                    </v-col>
                    <v-col

                      cols="6"
                      sm="6"
                      md="6"
                    >
                      <v-text-field
                        outlined
                        clearable

                        label="Confirmar Password"
                        type="password"
                        :rules="rulespass"
                      ></v-text-field>
                    </v-col>
                    <v-col
                      cols="6"
                      sm="6"
                      md="6"
                    >
                      <v-select
                        v-model="editedItem.nivel"
                        :items="roles"
                        item-value="id"
                        item-text="nombre"
                        required
                        label="Grupo"
                      >
                      </v-select>
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
                roles: [
                        { id: 4, nombre: 'Administrador' },
                        { id: 3, nombre: 'Supervisor' },
                        { id: 2, nombre: 'Gestion' },
                        { id: 1, nombre: 'Usuario' },
                ],
                rules: [
                    value => !!value || 'Requerido',
                    value => (value && value.length >= 3) || 'Minimo 3 caracteres',
                    value => (value && value.length <= 50) || 'Maximo 50 caracteres',
                    ],
                rulespass:[ v => !!v || 'Confirmar password', v => v === this.editedItem.password || 'El password no es el mismo que el anterior.'],
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
                this.editedItem =  this.getItem
                this.editedItem.email = this.editedItem.email.split('@')[0]
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
