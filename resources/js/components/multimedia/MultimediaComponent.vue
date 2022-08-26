<template>
<v-container>
    <v-form ref="form" v-on:submit.prevent>
        <v-row>
            <v-col cols="12" xl="8">
                <v-card class="elevation-0 py-8 pl-8 pr-8">
                    <v-container>
                        <v-row>
                            <v-col cols="12" xl="12">
                                <v-text-field v-model="vmodel_titulo" name="tituloPodcast" label="Título" placeholder="Título" hint="* Campo requerido" autocomplete="off" required persistent-hint outlined dense></v-text-field>
                            </v-col>
                            <v-col cols="12" xl="12">
                                <v-text-field v-model="vmodel_subtitulo" name="subtituloPodcast" label="Subtítulo" placeholder="Subtítulo" autocomplete="off" outlined dense></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" xl="12">
                                <ckeditor v-model="vmodel_ckeditor" :editor="editor" :config="editorConfig"></ckeditor>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-col>
            <v-col cols="12" xl="4">
                <v-expansion-panels v-model="vmodel_panel" hover focusable>
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
                                            <v-btn class="float-right mr-2" text outlined small color="primary" @click="multimedia(2)">Borrador</v-btn>
                                            <v-btn class="float-right mr-2" text outlined small color="primary" @click="multimedia(1)">Actualizar</v-btn>
                                        </span>
                                        <span v-else-if="estatus == 2">
                                            <v-btn class="float-right mr-2" text outlined small color="primary" @click="multimedia(2)">Guardar</v-btn>
                                            <v-btn class="float-right mr-2" text outlined small color="primary" @click="multimedia(1)">Publicar</v-btn>
                                        </span>
                                        <span v-else>
                                            <v-btn class="float-right mr-2" text outlined small color="primary" @click="multimedia(2)">Borrador</v-btn>
                                            <v-btn class="float-right mr-2" text outlined small color="primary" @click="multimedia(1)">Publicar</v-btn>
                                        </span>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header>Imagen Destacada</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" v-if="vmodel_imagenDestacada">
                                        <center>
                                            <v-avatar color="greey" size="350" width="450" tile hover class="elevation-1">
                                                <v-img :src="vmodel_imagenDestacada" aspect-ratio="1.7"></v-img>
                                            </v-avatar>
                                        </center>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-file-input v-model="vmodel_path_destacada" @change="mostrarImagenDestacada" hint="* Campo requerido" persistent-hint prepend-icon="mdi-camera" show-size truncate-length="20" accept="image/png, image/jpeg, image/jpg" placeholder="Imagen Destacada" label="Imagen Destacada"></v-file-input>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header>Programa</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="vmodel_programas" name="programa" label="Programa" placeholder="Programa" :items="listCatProgramas" item-text="titulo" item-value="id" clearable outlined dense></v-autocomplete>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header>Categoría</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-container>
                                <v-row>
                                    <v-col cols="12" class="p-0 m-0">
                                        <v-dialog v-model="dialog" persistent max-width="600px">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn class="float-right mr-2" text outlined small color="primary" v-bind="attrs" v-on="on">Nueva Categoría</v-btn>
                                            </template>
                                            <v-card>
                                                <v-card-title>
                                                    <span class="headline">Nueva Categoría</span>
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col cols="12" xl="12">
                                                                <v-text-field v-model="vmodel_new_categoria" name="newCategoria" label="Categoría" placeholder="Categoría" hint="* Campo requerido" autocomplete="off" required persistent-hint outlined dense></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="green darken-1" text v-on:click="agregarCategoria">Guardar</v-btn>
                                                    <v-btn color="primary darken-1" text @click="dialog = false">Cerrar</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="vmodel_categoria" name="categoria" label="Categoría" placeholder="Categoría" :items="listCatCategoria" item-text="categoria" item-value="id" clearable outlined dense></v-autocomplete>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header>Productor</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-container>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="vmodel_productor" name="productor" label="Productor" placeholder="Productor" autocomplete="off" outlined dense></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <v-expansion-panel>
                        <v-expansion-panel-header>Video</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-card elevation="0">
                                <v-tabs v-model="vmodel_tab">
                                    <v-tab>Insertar Video</v-tab>
                                    <v-tab>Subir Video</v-tab>
                                </v-tabs>
                                <v-tabs-items v-model="vmodel_tab">
                                    <!-- IFrame -->
                                    <v-tab-item>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-text-field v-model="vmodel_iframe" outlined dense hint="* Campo requerido" prepend-inner-icon="mdi-code-tags" persistent-hint placeholder="Ingrese su código de insercioón de video" label="Ingrese su código de insercioón de video"></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="12" v-html="vmodel_iframe">
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-tab-item>
                                    <!-- Upload -->
                                    <v-tab-item>
                                        <v-container>
                                            <v-row>
                                                <v-col cols="12">
                                                    <v-file-input v-model="vmodel_path_video" v-on:change="mostrarVideo" prepend-icon="mdi-play" show-size truncate-length="20" placeholder="Seleccionar video" label="Seleccionar video"></v-file-input>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col cols="12">
                                                    <video v-if="vmodel_video" class="col-12" :src="vmodel_video" controls controlsList="nodownload"></video>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-tab-item>
                                </v-tabs-items>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
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

import {
    string_to_slug
} from 'Helpers/helpers';

class MyUploadAdapter {

    constructor(loader) {
        this.loader = loader;
    }
    upload() {

        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                let formData = new FormData();
                formData.append('imagen', file);
                return axios.post('/upload-imagen', formData).then(response => {
                    console.log(response.data);
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
        dialog: false,
        vmodel_panel: 0,
        vmodel_titulo: '',
        vmodel_subtitulo: '',
        vmodel_imagenDestacada: '',
        vmodel_path_destacada: null,
        vmodel_ckeditor: '',
        editor: ClassicEditor,
        editorConfig: {
            extraPlugins: [
                MyUploadAdapterPlugin
            ],
        },
        vmodel_tab: 0,
        estatus: '',
        vmodel_iframe: '',
        vmodel_path_video: null,
        vmodel_video: '',
        vmodel_programas: '',
        listCatProgramas: [],
        vmodel_categoria: '',
        listCatCategoria: [],
        vmodel_new_categoria: '',
        vmodel_productor: '',
    }),
    beforeCreate() {},
    created() {
        this.catProgramas();
        this.categorias();
        this.obtenerInfo();
    },
    mounted() {},
    computed: {},
    watch: {},
    methods: {
        mostrarImagenDestacada(event) {
            try {
                this.vmodel_imagenDestacada = URL.createObjectURL(event);
            } catch (error) {
                this.vmodel_imagenDestacada = '';
                this.vmodel_path_destacada = null;
            }
        },

        mostrarVideo(event) {
            try {
                this.vmodel_video = URL.createObjectURL(event);
            } catch (error) {
                this.vmodel_video = '';
                this.vmodel_path_video = null;
            }
        },

        catProgramas() {
            axios.get('cat/programas').then(response => {
                this.listCatProgramas = response.data;
            });
        },

        categorias() {
            axios.get('cat/list/video').then(response => {
                this.listCatCategoria = response.data;
            }).catch(error => {
                axios.post('system/logs', {
                    debug: 'Fronted',
                    estatus: error.response.status,
                    menssage: error.response.statusText,
                    file: 'components\\multimedia\\MultimediaComponent.vue'
                }).catch(error => {});
            });
        },

        agregarCategoria() {
            if (this.vmodel_new_categoria != '') {
                axios.post('cat/add', {
                    categoria: this.vmodel_new_categoria,
                    slug: string_to_slug(this.vmodel_new_categoria),
                    tipo: 'video',
                }).then(response => {
                    if (response.data.answer) {
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', []]);
                        this.dialog = false;
                    } else {
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', []]);
                    }
                }).catch(error => {
                    axios.post('system/logs', {
                        debug: 'Fronted',
                        estatus: error.response.status,
                        menssage: error.response.statusText,
                        file: 'components\\multimedia\\MultimediaComponent.vue'
                    }).catch(error => {});
                });
            } else {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos.', 'error', true, '', ['top', 'right']]);
            }
        },

        multimedia(estatus) {
            if (this.$route.params.token) {
                this.editar(estatus);
            } else {
                this.guardar(estatus);
            }
        },

        guardar(estatus) {
            this.$refs.form.validate();
            if (this.vmodel_titulo != '' && this.vmodel_subtitulo != '' && this.vmodel_ckeditor != '' && this.vmodel_path_destacada != null && this.vmodel_path_audio != null &&
                this.vmodel_programas != '' && this.vmodel_categoria != '' && this.vmodel_productor != ''
            ) {
                let formData = new FormData();

                formData.append('titulo', this.vmodel_titulo);
                formData.append('subtitulo', this.vmodel_subtitulo);
                formData.append('contenido', this.vmodel_ckeditor);
                formData.append('imagen_destacada', this.vmodel_path_destacada);
                formData.append('audio', this.vmodel_path_audio);
                formData.append('programa', this.vmodel_programas);
                formData.append('categoria', this.vmodel_categoria);
                formData.append('productor', this.vmodel_productor);
                formData.append('estatus', estatus);

                axios.post('podcast/add', formData).then(response => {
                    if (response.data.answer) {
                        this.$refs.form.reset();
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        this.$router.push({
                            path: '/podcast'
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
                        } else if (response.data.audio) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.audio, 'error', true, '', []]);
                        } else if (response.data.programa) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.programa, 'error', true, '', []]);
                        } else if (response.data.categoria) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.categoria, 'error', true, '', []]);
                        } else if (response.data.productor) {
                            this.$store.dispatch('sanckbarsMessage', [response.data.productor, 'error', true, '', []]);
                        } else {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', []]);
                        }
                    }
                }).catch(error => {
                    axios.post('system/logs', {
                        debug: 'Fronted',
                        estatus: error.response.status,
                        menssage: error.response.statusText,
                        file: 'components\\multimedia\\MultimediaComponent.vue'
                    }).catch(error => {});
                });
            } else {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos.', 'error', true, '', ['top', 'right']]);
            }
        },

        editar(estatus) {
            this.$refs.form.validate();
            if (this.vmodel_titulo != '' && this.vmodel_subtitulo != '' && this.vmodel_ckeditor != '' &&
                this.vmodel_programas != '' && this.vmodel_categoria != '' && this.vmodel_productor != '') {
                let formData = new FormData();

                formData.append('token', this.$route.params.token);
                formData.append('titulo', this.vmodel_titulo);
                formData.append('subtitulo', this.vmodel_subtitulo);
                formData.append('contenido', this.vmodel_ckeditor);
                formData.append('imagen_destacada', this.vmodel_path_destacada);
                formData.append('audio', this.vmodel_path_audio);
                formData.append('programa', this.vmodel_programas);
                formData.append('categoria', this.vmodel_categoria);
                formData.append('productor', this.vmodel_productor);
                formData.append('estatus', estatus);

                axios.post('podcast/edit', formData).then(response => {
                    if (response.data.answer) {
                        this.$refs.form.reset();
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'success', true, '', ['top', 'right']]);
                        this.$router.push({
                            path: '/podcast'
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
                        file: 'components\\multimedia\\MultimediaComponent.vue'
                    }).catch(error => {});
                });
            } else {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos.', 'error', true, '', ['top', 'right']]);
            }
        },

        obtenerInfo() {
            if (this.$route.params.token) {
                axios.post('podcast/detail', {
                    token: this.$route.params.token,
                }).then(response => {
                    if (!response.data.answer) {
                        this.vmodel_titulo = response.data.titulo;
                        this.vmodel_subtitulo = response.data.subtitulo;
                        this.vmodel_ckeditor = response.data.contenido;
                        this.vmodel_imagenDestacada = response.data.imagen_destacada;
                        this.estatus = response.data.estatus;
                        this.vmodel_productor = response.data.productor;
                        this.vmodel_audio = response.data.audio;
                        this.vmodel_programas = response.data.programas;
                        this.vmodel_categoria = response.data.categoria;
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
                        file: 'components\\multimedia\\MultimediaComponent.vue'
                    }).catch(error => {});
                });
            }
        },
    }
}
</script>
