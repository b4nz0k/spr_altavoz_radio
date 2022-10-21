<template>
<v-container>
    <v-form ref="form" v-on:submit.prevent>
        <v-row>


                <v-col cols="12" lg="12" xl="12">
                <v-card class="elevation-0 pl-8 pr-8">
                    <v-container>
                        <v-row>
<v-col cols="6">
    <v-card class="my-5 mx-auto"
                >
                    <v-system-bar
                    color="indigo darken-2"
                    dark
                    ><h3>Imagen</h3></v-system-bar>

                           <v-container class="pa-4 text-center mt-3">
                                <v-row>
                                    <v-col cols="12"  v-if="vmodel_imagenDestacada">
                                        <center>
                                            <v-avatar color="greey" size="75%" width="75%" tile hover class="elevation-1">
                                                <v-img :src="vmodel_imagenDestacada" aspect-ratio="1.7"></v-img>
                                            </v-avatar>
                                        </center>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-file-input v-model="vmodel_path_destacada" @change="mostrarImagenDestacada" hint="* Campo requerido" persistent-hint prepend-icon="mdi-camera" show-size
                                        truncate-length="20" accept="image/png, image/jpeg, image/jpg" placeholder="Imagen Destacada" label="Imagen Destacada"></v-file-input>
                                    </v-col>
                                    <v-col cols="12" class="d-flex justify-end">
                                        <widget-galeria file_tipo="imagen"></widget-galeria>
                                    </v-col>
                                </v-row>
                            </v-container>

                </v-card>
</v-col>
<v-col cols="6">
    <v-card class="my-5 mx-auto"
                >
                    <v-system-bar
                    color="indigo darken-2"
                    dark
                    ><h3>Audio</h3></v-system-bar>

                           <v-container class="pa-4 text-center mt-3">
                                <v-row
                                align="center"
                                justify="center">
                                <v-col cols="12"  v-if="vmodel_audio"
                                    >
                                        <vuetifyAudio :file="vmodel_audio" color="success" :ended="audioFinish" downloadable></vuetifyAudio>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-file-input v-model="vmodel_path_audio" v-on:change="mostrarAudio" hint="* Campo requerido" persistent-hint prepend-icon="mdi-cast-audio" show-size truncate-length="20" accept="audio/mp3" placeholder="Audio" label="Audio"></v-file-input>
                                    </v-col>
                                    <v-col cols="12" class="d-flex justify-end">
                                        <widget-galeria file_tipo="audio"></widget-galeria>
                                    </v-col>
                                </v-row>
                            </v-container>

                </v-card>
</v-col>

                            <v-col cols="12" xl="12">
<!-- COmponente  -->
<SelectPaginateVue
    :itemModel="vmodel_programas"
    :paginacion="url.getProgramas"
    :paginacionSearch="url.getProgramasSearch"
    :retornarItem="catProgramas"
    itemValue="id"
    itemText="titulo"
    itemLabel="Programas"
    slotTipo="programas"
    >
</SelectPaginateVue>
<!--                                 <v-autocomplete
                                label="Programas"
                                v-model="vmodel_programas"
                                :items="listCatProgramas"
                                item-text="titulo"
                                item-value="id"
                                outlined
                                clearable
                                >
                                  </v-autocomplete>
 -->
</v-col>
                        <v-col cols="6" xl="6" >
 <SelectPaginateVue
    :itemModel="vmodel_productor"
    :paginacion="url.getProductores"
    :paginacionSearch="url.getProductoresSearch"
    :retornarItem="catProductores"
    itemValue="id"
    itemText="nombre"
    itemLabel="Productor"
    />
<!--                                 <v-autocomplete
                                justify-end
                                label="Productor"
                                v-model="vmodel_productor"
                                :items="listCatProductores"
                                item-text="nombre"
                                item-value="id"
                                outlined
                                clearable
                                >
                                <template v-slot:selection="data">

                                    <v-avatar color="indigo">
                                        <v-icon dark>
                                            mdi-account-circle
                                        </v-icon>
                                    </v-avatar>
                                    {{ data.item.nombre }}
                                </template>
                                </v-autocomplete>
 -->                            </v-col>
                            <v-col cols="6" xl="6" >

<SelectPaginateVue
    :itemModel="vmodel_categoria"
    :paginacion="url.getCategorias"
    :paginacionSearch="url.getCategoriasSearch"
    :retornarItem="categorias"
    itemValue="id"
    itemText="categoria"
    itemLabel="Categoria"
    />
<!--                                 <v-select
                                justify-end
                                label="Categoria"
                                v-model="vmodel_categoria"
                                :items="listCatCategoria"
                                item-text="categoria"
                                item-value="id"
                                outlined
                                clearable
                                >

                                </v-select>
 -->                            </v-col>
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
                            <v-col cols="12">
                            <v-card-title class="mb-5">
                            <v-spacer></v-spacer>
                                <v-btn
                                @click="podcast(1)"
                                 dark
                                x-large
                                left
                                rounded
                                class="mx-3 mt-8"
                                color="success"
                                >
                                    {{ estatus == 1 ? 'Actualizar' : 'Publicar' }}
                                </v-btn>
                                <v-btn
                                @click="podcast(2)"
                                outlined dark
                                x-large
                                right
                                rounded
                                class="mx-3 mt-8"
                                color="primary"
                                >
                                    {{ estatus == 2 ? 'Guardar' : 'Borrador'}}
                                </v-btn>
                    </v-card-title>

                            </v-col>
                        </v-row>

                    </v-container>
                </v-card>
            </v-col>

            <v-col cols="12" lg="4" xl="4">



            </v-col>

        </v-row>
    </v-form>
</v-container>
</template>

<script>
import SelectPaginateVue from '../../components/utils/SelectPaginate.vue'

import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import vuetifyAudio from './audioTemplate.vue';
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
        ckeditor: CKEditor.component,
        vuetifyAudio,
        SelectPaginateVue
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
        estatus: '',
        vmodel_path_audio: null,
        vmodel_audio: null,
        vmodel_programas: '',
        listCatProgramas: [],
        vmodel_categoria: '',
        listCatCategoria: [],
        listCatProductores: [],
        vmodel_new_categoria: '',
        vmodel_productor: '',
        // Audio config
        audio: null,
        url: {
            getProgramas: 'programa/list/all?page=',
            getProgramasSearch: 'programa/search?page=',
            getProductores: 'productor/list?page=',
            getProductoresSearch: 'productor/search?page=',
            getCategorias: 'cat/list?page=',
            getCategoriasSearch: 'cat/search?page=',

        }

    }),
    beforeCreate() {},
    created() {
        this.obtenerInfo();

        // this.catProgramas();
        // this.categorias();
    },
    mounted() {},
    computed: {},
    watch: {},
    methods: {

        audioFinish () {
				this.msgs.push('The audio finished.')
        },

        mostrarImagenDestacada(event) {
            try {
                this.vmodel_imagenDestacada = URL.createObjectURL(event);
            } catch (error) {
                this.vmodel_imagenDestacada = '';
                this.vmodel_path_destacada = null;
            }
        },

        mostrarAudio(event) {
            try {
                this.vmodel_audio = URL.createObjectURL(event);
            } catch (error) {
                this.vmodel_audio = '';
                this.vmodel_path_audio = null;
            }
        },

        catProgramas(programa) {
            this.vmodel_programas = programa
            console.log('el programa seleccionado es ',this.vmodel_programas )
        },

        catProductores(productor) {
            this.vmodel_productor = productor
            console.log('el productor seleccionado es ',this.vmodel_productor )
        },

        categorias(categoria) {
            this.vmodel_categoria = categoria
            console.log('la categoria seleccionada es ',this.vmodel_categoria )
        },

        agregarCategoria() {
            if (this.vmodel_new_categoria != '') {
                axios.post('cat/add', {
                    categoria: this.vmodel_new_categoria,
                    slug: string_to_slug(this.vmodel_new_categoria),
                    tipo: 'podcast',
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
                        file: 'components\\podcast\\PodcastComponent.vue'
                    }).catch(error => {});
                });
            } else {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos.', 'error', true, '', ['top', 'right']]);
            }
        },

        podcast(estatus) {
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
                    console.log(response.data)
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
                        file: 'components\\podcast\\PodcastComponent.vue'
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
/*                 if (this.vmodel_path_audio !== null)
                    console.log('vmodel_path_audio no es nulo')
                    console.log(this.vmodel_path_audio)
                if (this.vmodel_path_destacada !== null)
                    console.log('vmodel_path_destacada no es nulo')
                    console.log(this.vmodel_path_destacada)


                if (this.vmodel_path_audio == null)
                    console.log('vmodel_path_audio es nulo')
                if (this.vmodel_path_destacada == null)
                    console.log('vmodel_path_destacada es nulo')
                return 0; */

                formData.append('token', this.$route.params.token);
                formData.append('titulo', this.vmodel_titulo);
                formData.append('subtitulo', this.vmodel_subtitulo);
                formData.append('contenido', this.vmodel_ckeditor);
                formData.append('imagen_destacada', this.vmodel_path_destacada);
                formData.append('audio', this.vmodel_path_audio);
                formData.append('programa', Number(this.vmodel_programas));
                formData.append('categoria', Number(this.vmodel_categoria));
                formData.append('productor', Number(this.vmodel_productor));
                formData.append('estatus', Number(estatus));

                console.log(formData);
                axios.post('podcast/edit', formData).then(response => {

                    console.log(formData)
                    console.log(response.data)

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
                        file: 'components\\podcast\\PodcastComponent.vue'
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
                        file: 'components\\podcast\\PodcastComponent.vue'
                    }).catch(error => {});
                });
            }
        },
    }
}
</script>
