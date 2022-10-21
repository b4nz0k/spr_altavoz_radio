<template>
<v-dialog v-model="dialog" transition="dialog-top-transition" persistent max-width="850" scrollable>
    <template v-slot:activator="{ on, attrs }">
        <v-btn color="primary" outlined text small v-bind="attrs" v-on="on" v-on:click="galeria()">Galería</v-btn>
    </template>
    <template v-slot:default="dialog">
        <v-card>
            <v-toolbar color="primary" dark>Galería</v-toolbar>
            <v-card-text style="height: 450px;">
                <!-- <v-responsive class="overflow-y-auto" max-height="500"> -->
                    <v-item-group v-model="vmodel_select_imagen">
                        <v-container>
                            <v-row>
                                <v-col v-for="galeria in listGaleria" :key="galeria.id" class="d-flex child-flex" cols="4">
                                    <v-item v-slot:default="{ active, toggle, overlay }">
                                        <v-card flat tile class="d-flex" @click="toggle">
                                            <v-img :src="`/upload/` + galeria.file_name_renombrado + '.'+galeria.file_extension" :lazy-src="`/upload/` + galeria.file_name_renombrado + '.'+galeria.file_extension" aspect-ratio="1" class="grey lighten-2">
                                                <template v-slot:placeholder>
                                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                                    </v-row>
                                                </template>
                                                <v-scroll-y-transition>
                                                    <div class="display-3 flex-grow-1 text-center">
                                                        <v-overlay v-if="active" :absolute="absolute" :value="overlay">
                                                            <v-btn color="success" @click="overlay = false">
                                                                Cancelar
                                                            </v-btn>
                                                        </v-overlay>
                                                    </div>
                                                </v-scroll-y-transition>
                                            </v-img>
                                        </v-card>
                                    </v-item>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-item-group>
                <!-- </v-responsive> -->
            </v-card-text>
            <v-card-actions class="justify-end">
                <v-btn color="primary darken-1" text @click="dialog.value = false">Cerrar</v-btn>
                <v-btn color="green darken-1" text v-on:click="aceptar()">Aceptar</v-btn>
            </v-card-actions>
        </v-card>
    </template>
</v-dialog>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex";

export default {
    props: ['file_tipo'],
    components: {},
    data: () => ({
        dialog: false,
        absolute: true,
        overlay: false,
        vmodel_select_imagen: null,
        listGaleria: [],
    }),
    beforeCreate() {},
    created() {
    },
    mounted() {},
    computed: {},
    watch: {},
    methods: {
        ...mapMutations(['setDialogGaleriaPath']),
        galeria() {
            axios.get('/gallery/'+this.file_tipo).then(response => {
                this.listGaleria = response.data;
            }).catch(error => {
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\widget\\WidgetGaleria.vue'
                }).catch(error => {});
            });
        },
        
        aceptar() {
            this.setDialogGaleriaPath('/upload/' + this.listGaleria[this.vmodel_select_imagen].file_name_renombrado + '.' + this.listGaleria[this.vmodel_select_imagen].file_extension);
            this.dialog = false;
        },
    },
}
</script>
