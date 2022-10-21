<template>
    <v-row>
        <v-col cols="6">
            <v-card
                class="mx-auto"
                max-width="90%"
                outlined
                >
                <v-list-item three-line>
                    <v-list-item-content>
                    <div class="text-overline mb-4">
                        Perfil
                    </div>
                    <v-list-item-title class="text-h5 mb-1">
                    {{infoUsuario.nombre}}
                    </v-list-item-title>
                    <v-list-item-subtitle>Estacion: {{infoUsuario.estacion}} </v-list-item-subtitle>
                    <v-list-item-subtitle>Correo: {{infoUsuario.email}} </v-list-item-subtitle>
                    <v-list-item-subtitle>Nivel: {{infoUsuario.nivel}} </v-list-item-subtitle>
                    <v-list-item-subtitle>{{infoUsuario}} </v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                    tile
                    size="80"
                    color="grey"
                    :src="infoUsuario.image"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn outlined rounded text > Editar
                    </v-btn>
                </v-card-actions>

            </v-card>
            {{perfil}}
        </v-col>
        <v-col cols="6">
            <v-card
                class="mx-auto"
                max-width="90%"
                outlined
                >
                <v-card-title>
                    Form
                </v-card-title>
                <v-card-text>
                    <v-text-field class="mb-2"
                    label="Nombre"
                    outlined
                    clearable
                    v-model="perfil.name"
                    ></v-text-field>
                    <v-text-field class="mb-2"
                    label="Email"
                    outlined
                    clearable
                    v-model="perfil.email"
                    ></v-text-field>
                </v-card-text>
                <v-btn
                color="success" dark
                class="mb-4 ml-4"
                >Actualizar</v-btn>
            </v-card>

        </v-col>
    </v-row>
  </template>
<script>
import { mapGetters } from 'vuex'
  export default {
    name: 'CardUsuario',
    computed: {
        ...mapGetters(['infoUsuario']),
    },
    data() {
        return {
            perfil: {
                name: '',
                email:'',
                pass1: null,
                pass2: null,
            }
        }
    },
    mounted() {
        this.inicio();
    },
    methods:{
        inicio() {
            this.perfil.name  = this.infoUsuario.nombre
            this.perfil.email  = this.infoUsuario.email
            console.log('perfil ',this.perfil)
            console.log('informacion de usuario', this.infoUsuario)
        }
    }
  }
</script>
