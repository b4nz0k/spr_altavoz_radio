<template>
<v-container>
    <v-form ref="form" v-on:submit.prevent>
        <v-row>
            <v-col cols="12" xl="8">
                <v-card class="elevation-0 py-8 pl-8 pr-8">
                    <v-container>
                        <v-row>
                            <v-col cols="12" xl="12">
                                <v-text-field v-model="vmodel_titulo" name="tituloPrograma" label="Título" placeholder="Título" hint="* Campo requerido" autocomplete="off" required persistent-hint outlined dense></v-text-field>
                            </v-col>
                            <v-col cols="12" xl="12">
                                <v-text-field v-model="vmodel_subtitulo" name="subtituloPrograma" label="Subtítulo" placeholder="Subtítulo" hint="* Campo requerido" autocomplete="off" required persistent-hint outlined dense></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" xl="12">
                                <ckeditor v-model="vmodel_ckeditor" :editor="editor" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                        <!-- <v-row>
                            <v-col cols="12" xl="12">
                                <v-chip class="ma-2">Estacion(es) de Radio</v-chip>
                            </v-col>
                            <v-col cols="12" xl="12">
                                <template v-for="estacion in listChipsEstaciones">
                                    <v-chip class="ma-2" :key="estacion" color="primary" outlined>{{estacion}}</v-chip>
                                </template>
                            </v-col>
                        </v-row> -->
                    </v-container>
                </v-card>
            </v-col>
            <v-col cols="12" xl="4">
                <v-row>
                    <v-col cols="12">
                        <v-expansion-panels v-model="vmodel_panel" hover focusable>
                            <v-expansion-panel>
                                <v-expansion-panel-header>Imagen Destacada</v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" v-if="vmodel_imagenDestacada">
                                                <center>
                                                    <v-avatar color="primary" size="350" width="450" tile hover class="elevation-1">
                                                        <v-img :src="vmodel_imagenDestacada" aspect-ratio="1.7"></v-img>
                                                    </v-avatar>
                                                </center>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-file-input v-model="vmodel_path_destacada" @change="mostrarImagenDestacada" hint="* Campo requerido" persistent-hint prepend-icon="mdi-camera" required show-size truncate-length="20" accept="image/png, image/jpeg, image/jpg" placeholder="Imagen Destacada" label="Imagen Destacada"></v-file-input>
                                            </v-col>
                                            <v-col cols="12" class="d-flex justify-end">
                                                <widget-galeria file_tipo="imagen"></widget-galeria>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                            <v-expansion-panel>
                                <v-expansion-panel-header>Imagen Banner</v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" v-if="vmodel_imagenBanner">
                                                <center>
                                                    <v-avatar color="greey" size="350" width="450" tile hover class="elevation-1">
                                                        <v-img :src="vmodel_imagenBanner" aspect-ratio="1.7"></v-img>
                                                    </v-avatar>
                                                </center>

                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <v-file-input v-model="vmodel_path_banner" @change="mostrarImagenBanner" hint="* Campo requerido" persistent-hint prepend-icon="mdi-camera" required show-size truncate-length="20" accept="image/png, image/jpeg, image/jpg" placeholder="Imagen Banner" label="Imagen Banner"></v-file-input>
                                            </v-col>
                                            <v-col cols="12" class="d-flex justify-end">
                                                <widget-galeria file_tipo="imagen"></widget-galeria>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <ul>
                                                    <li>El banner debe tener las siguientes dimensiones 1920 x 1080.</li>
                                                </ul>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                            <v-expansion-panel>
                                <v-expansion-panel-header>Autor</v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <!-- <v-text-field v-model="vmodel_autor" name="autor" label="Autor" placeholder="Autor" outlined dense></v-text-field> -->
                                                <v-autocomplete v-model="vmodel_autor" name="autor" label="Autor" placeholder="Autor" :items="listAutores" item-text="autor" item-value="id" clearable outlined dense></v-autocomplete>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-col>
                    <v-col cols="12">
                        <v-expansion-panels v-model="vmodel_panel_publish" hover focusable>
                            <v-expansion-panel>
                                <v-expansion-panel-header>Publicar</v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12">
                                                <ul>
                                                    <li>Guardar, no será público hasta que no cambie su estatus de publicación.</li>
                                                    <li>Publicar, inmediatamente se verá reflejado en el portal.</li>
                                                </ul>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="12">
                                                <span v-if="estatus == 1">
                                                    <v-btn class="float-right mr-2" text outlined small color="primary" @click="programa(2)">Borrador</v-btn>
                                                    <v-btn class="float-right mr-2" text outlined small color="primary" @click="programa(1)">Actualizar</v-btn>
                                                </span>
                                                <span v-else-if="estatus == 2">
                                                    <v-btn class="float-right mr-2" text outlined small color="primary" @click="programa(2)">Guardar</v-btn>
                                                    <v-btn class="float-right mr-2" text outlined small color="primary" @click="programa(1)">Publicar</v-btn>
                                                </span>
                                                <span v-else>
                                                    <v-btn class="float-right mr-2" text outlined small color="primary" @click="programa(2)">Borrador</v-btn>
                                                    <v-btn class="float-right mr-2" text outlined small color="primary" @click="programa(1)">Publicar</v-btn>
                                                </span>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-form>
</v-container>
</template>

<script>
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex";
class MyUploadAdapter {

    constructor(loader) {
        this.loader = loader;
    }
    upload() {

        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                let formData = new FormData();
                formData.append('imagen', file);
                return axios.post('/ckeditor/imagen', formData).then(response => {
                    resolve({
                        default: response.data
                    });
                });
            }));
    }

}

function MyUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
        return new MyUploadAdapter(loader);
    };
}
export default {
    props: [],
    components: {
        ckeditor: CKEditor.component
    },
    data: () => ({
        vmodel_panel: 0,
        vmodel_panel_publish: 0,
        vmodel_titulo: '',
        vmodel_subtitulo: '',
        vmodel_imagenDestacada: '',
        vmodel_imagenBanner: '',
        vmodel_path_destacada: null,
        vmodel_path_banner: null,
        vmodel_autor: '',
        vmodel_ckeditor: '',
        editor: ClassicEditor,
        editorConfig: {
            extraPlugins: [
                MyUploadAdapterPlugin
            ],
        },
        vmodel_estacion: [],
        listCatEstaciones: [],
        listChipsEstaciones: [],
        listAutores: [],
        estatus: '',
    }),
    beforeCreate() {},
    created() {
        //this.catEstacionRadio();
        this.obtenerInfo();
    },
    mounted() {},
    computed: {
        ...mapGetters(['getDialogGaleriaPath',]),
    },
    watch: {
        getDialogGaleriaPath(val) {
            if (val != '') {
                if (this.vmodel_panel == 0) {
                    this.vmodel_imagenDestacada = val;
                } else if (this.vmodel_panel == 1) {
                    this.vmodel_imagenBanner = val;
                }
                setTimeout(() => {
                    this.setDialogGaleriaPath('');
                }, 500);
            }
        }
    },
    methods: {
        ...mapMutations(['setDialogGaleriaPath']),
        catAutores() {
            axios.get('').then(response => {

            }).catch(error => {

            });
        },

        changeEstaciones() {
            this.listChipsEstaciones = [];
            this.vmodel_estacion.forEach(estacion => {
                this.listCatEstaciones.find(element => {
                    if (element.id == estacion) {
                        this.listChipsEstaciones.push(element.estacion);
                    }
                })
            });
        },

        catEstacionRadio() {
            axios.get('cat/estacion-radio').then(response => {
                this.listCatEstaciones = response.data;
            });
        },

        removeEstacion(item) {
            const index = this.vmodel_estacion.indexOf(item.id)
            if (index >= 0) {
                this.vmodel_estacion.splice(index, 1);
                this.changeEstaciones();
            }
        },

        mostrarImagenDestacada(event) {
            try {
                this.vmodel_imagenDestacada = URL.createObjectURL(event);
            } catch (error) {
                this.vmodel_imagenDestacada = '';
                this.vmodel_path_destacada = null;
            }
        },

        mostrarImagenBanner(event) {
            try {
                this.vmodel_imagenBanner = URL.createObjectURL(event);
            } catch (error) {
                this.vmodel_imagenBanner = '';
                this.vmodel_path_banner = null;
            }
        },

        programa(estatus) {
            if (this.$route.params.token) {
                this.editar(estatus);
            } else {
                this.guardar(estatus);
            }
        },

        guardar(estatus) {
            this.$refs.form.validate();
            if (this.vmodel_titulo != '' && this.vmodel_subtitulo != '' && this.vmodel_path_destacada != null && this.vmodel_path_banner != null) {
                let formData = new FormData();

                formData.append('titulo', this.vmodel_titulo);
                formData.append('subtitulo', this.vmodel_subtitulo);
                formData.append('contenido', this.vmodel_ckeditor);
                formData.append('imagen_destacada', this.vmodel_path_destacada);
                formData.append('imagen_banner', this.vmodel_path_banner);
                formData.append('estatus', estatus);
                // formData.append('estaciones', this.vmodel_estacion);

                axios.post('programa/add', formData).then(response => {
                    if (response.data.answer) {
                        this.$refs.form.reset();
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        this.$router.push({
                            path: '/programa'
                        }).catch(error => {});
                    } else {
                        if (response.data.titulo) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.titulo, 'error', true, '', []]);
                        } else if (response.data.subtitulo) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.subtitulo, 'error', true, '', []]);
                        } else if (response.data.contenido) {
                            this.$store.dispatch('sanckbarsMessage', ['Ingrese contenido al programa.', 'error', true, '', []]);
                        } else if (response.data.imagen_destacada) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.destacada, 'error', true, '', []]);
                        } else if (response.data.imagen_banner) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.banner, 'error', true, '', []]);
                        } else {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', []]);
                        }
                    }
                }).catch(error => {
                    axios.post('system/logs', {
                        debug: 'Fronted',
                        estatus: error.response.status,
                        menssage: error.response.statusText,
                        file: 'components\\programa\\ProgramaComponent.vue'
                    }).catch(error => {});
                });
            } else {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos.', 'error', true, '', ['top', 'right']]);
            }
        },

        editar(estatus) {
            this.$refs.form.validate();
            if (this.vmodel_titulo != '' && this.vmodel_subtitulo != '') {
                let formData = new FormData();

                formData.append('token', this.$route.params.token);
                formData.append('titulo', this.vmodel_titulo);
                formData.append('subtitulo', this.vmodel_subtitulo);
                formData.append('contenido', this.vmodel_ckeditor);
                formData.append('imagen_destacada', this.vmodel_path_destacada);
                formData.append('imagen_banner', this.vmodel_path_banner);
                formData.append('estatus', estatus);
                // formData.append('estaciones', this.vmodel_estacion);

                axios.post('programa/edit', formData).then(response => {
                    if (response.data.answer) {
                        this.$refs.form.reset();
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        this.$router.push({
                            path: '/programa'
                        }).catch(error => {});
                    } else {
                        if (response.data.titulo) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.titulo, 'error', true, '', []]);
                        } else if (response.data.subtitulo) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.subtitulo, 'error', true, '', []]);
                        } else if (response.data.contenido) {
                            this.$store.dispatch('sanckbarsMessage', ['Ingrese contenido al programa.', 'error', true, '', []]);
                        } else if (response.data.imagen_destacada) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.destacada, 'error', true, '', []]);
                        } else if (response.data.imagen_banner) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.banner, 'error', true, '', []]);
                        } else {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', []]);
                        }
                    }
                }).catch(error => {
                    axios.post('system/logs', {
                        debug: 'Fronted',
                        estatus: error.response.status,
                        menssage: error.response.statusText,
                        file: 'components\\programa\\ProgramaComponent.vue'
                    }).catch(error => {});
                });
            } else {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos.', 'error', true, '', ['top', 'right']]);
            }
        },

        obtenerInfo() {
            if (this.$route.params.token) {
                axios.post('programa/detail', {
                    token: this.$route.params.token,
                }).then(response => {
                    if (!response.data.answer) {
                        this.vmodel_titulo = response.data.titulo;
                        this.vmodel_subtitulo = response.data.subtitulo;
                        this.vmodel_ckeditor = response.data.contenido;
                        // response.data.programas_estacion.forEach(element => {
                        //     this.vmodel_estacion.push(element.estacion_radio_id);
                        // });
                        // this.changeEstaciones();
                        this.vmodel_imagenDestacada = response.data.imagen_destacada;
                        this.vmodel_imagenBanner = response.data.imagen_banner;
                        this.estatus = response.data.estatus;
                    } else {
                        this.$router.push({
                            path: '/page-error'
                        }).catch(error => {});
                    }
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', []]);
                    }
                    if (error.response.status == 419) {
                        this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                    }
                    if (error.response.status == 500) {
                        this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', []]);
                    }
                    axios.post('system/logs', {
                        debug: 'Fronted',
                        estatus: error.response.status,
                        menssage: error.response.statusText,
                        file: 'components\\programa\\ProgramaComponent.vue'
                    }).catch(error => {});
                });
            }
        },
    },
}
</script>

<style>
.ck-content {
    height: 500px;
}
</style>
