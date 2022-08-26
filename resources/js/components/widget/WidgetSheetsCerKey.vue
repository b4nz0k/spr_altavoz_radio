<template>
<div>
    <v-bottom-sheet v-model="vmodel_sheet" persistent>
        <v-sheet>
            <v-form ref="form" v-on:submit.prevent>
                <v-container>
                    <v-row>
                        <v-col cols="12" lg="12" xl="12">
                            <v-chip color="primary" label text-color="white">
                                <h3>Confirmar Registro</h3>
                            </v-chip>
                            <v-divider></v-divider>
                        </v-col>
                    </v-row>
                    <v-row class="lg-8" justify-lg="center">
                        <v-col cols="12" lg="4" xl="4">
                            <v-file-input v-model="vmodel_cer" name="cer" v-on:change="changeCer" ref="fileInputCer" :rules="rulesCer" accept=".cer" chips show-size label="Certificado (.cer)" dense outlined required></v-file-input>
                        </v-col>
                        <v-col cols="12" lg="4" xl="4">
                            <v-file-input v-model="vmodel_key" name="key" v-on:change="changeKey" ref="fileInputKey" :rules="rulesKey" accept=".key" chips show-size label="Clave privada (.key)" dense outlined required></v-file-input>
                        </v-col>
                    </v-row>
                    <v-row class="lg-8" justify-lg="center">
                        <v-col cols="12" lg="4" xl="4">
                            <v-text-field v-model="vmodel_rfc" name="rfc" label="RFC" dense outlined disabled></v-text-field>
                        </v-col>
                        <v-col cols="12" lg="4" xl="4">
                            <v-text-field v-model="vmodel_password" name="password" @keyup.enter="confirmar()" dense outlined :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :rules="rulesPassword" :type="showPassword ? 'text' : 'password'" label="Contraseña de clave privada" @click:append="showPassword = !showPassword"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row class="lg-8" justify-lg="center">
                        <v-col cols="12" lg="8" xl="8">
                            <v-row align="end" justify="end">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn color="error" class="ma-1" outlined v-bind="attrs" v-on="on" @click="close()">Cerrar</v-btn>
                                    </template>
                                    <span>Cancelar</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn color="success" class="ma-1" outlined v-bind="attrs" v-on="on" @click="confirmar()" :disabled="!formIsValid">Aceptar</v-btn>
                                    </template>
                                    <span>Aprobar registro</span>
                                </v-tooltip>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
        </v-sheet>
    </v-bottom-sheet>
</div>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex";

export default {
    props: [],
    components: {},
    data: () => ({
        vmodel_sheet: false,
        showPassword: false,
        rulesCer: [
            v => !!v || 'Certificado (.cer) es requerido',
            v => !v || v.size < 1000000 || 'El tamaño del archivo debe ser inferior a 1 MB',
        ],
        rulesKey: [
            v => !!v || 'Clave privada (.key) es requerido',
            v => !v || v.size < 1000000 || 'El tamaño del archivo debe ser inferior a 1 MB',
        ],
        rulesPassword: [
            v => !!v || "Contraseña de clave privada es requerida",
        ],
        vmodel_cer: null,
        vmodel_key: null,
        vmodel_rfc: '',
        vmodel_password: '',
    }),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {
        ...mapGetters(['getSheetCerKey']),

        formIsValid() {
            return (
                this.vmodel_cer &&
                this.vmodel_key &&
                this.vmodel_password
            )
        },
    },
    watch: {
        getSheetCerKey(val) {
            this.vmodel_sheet = val;
        },

        vmodel_sheet(val) {
            if (!val) {
                this.setSheetCerKey(false);
            }
        }
    },
    methods: {
        ...mapMutations(['setSheetCerKey', 'setConfirmacionCerKey', 'setVmodel_sheetLogin', 'setDialogConfirStatus']),
        changeCer() {
            if (this.vmodel_cer != null) {
                let cer = this.vmodel_cer.name;
                let cer_extencion = cer.substring(cer.lastIndexOf('.')).toLowerCase();
                if (cer_extencion != '.cer') {
                    this.$store.dispatch('sanckbarsMessage', ['El archivo seleccionado no es valido', 'error', true, '', []]);
                } else {
                    let formData = new FormData();
                    formData.append('cer', this.vmodel_cer);

                    axios.post('cerkey/rfc', formData).then(response => {
                        if (response.data.answer) {
                            this.vmodel_rfc = response.data.rfc;
                        } else {
                            this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true, '', []]);
                        }
                    }).catch(error => {
                        if (error.response.status == 401) {
                            this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', []]);
                            this.setVmodel_sheetLogin(true);
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
                            file: 'components\\widget\\WidgetSheetsCerKey.vue'
                        }).catch(error => {});
                    });
                }
            }
        },

        changeKey() {
            if (this.vmodel_key != null) {
                let key = this.vmodel_key.name;
                let key_extencion = key.substring(key.lastIndexOf('.')).toLowerCase();
                if (key_extencion != '.key') {
                    this.$store.dispatch('sanckbarsMessage', ['El archivo seleccionado no es valido', 'error', true]);
                }
            }
        },

        confirmar() {
            this.$refs.form.validate();
            if (this.vmodel_cer === null || this.vmodel_key === null || this.vmodel_password === '') {
                this.$store.dispatch('sanckbarsMessage', ['Por favor llene los campos requeridos', 'error', true]);
            } else {
                let formData = new FormData();
                formData.append('cer', this.vmodel_cer);
                formData.append('key', this.vmodel_key);
                formData.append('password', this.vmodel_password);

                axios.post('cerkey/confirmacion', formData).then(response => {
                    if (response.data.answer) {
                        this.$refs.form.reset();
                        this.setConfirmacionCerKey(true);
                        this.setDialogConfirStatus(false);
                    } else {
                        this.$store.dispatch('sanckbarsMessage', [response.data.msg, 'error', true]);
                        this.setConfirmacionCerKey(false);
                    }
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true]);
                    }
                    if (error.response.status == 419) {
                        this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true]);
                    }
                    if (error.response.status == 500) {
                        this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true]);
                    }
                    axios.post('system/logs', {
                        debug: 'Fronted',
                        estatus: error.response.status,
                        menssage: error.response.statusText,
                        file: 'components\\widget\\WidgetSheetsCerKey.vue'
                    }).catch(error => {
                    });
                });
            }
        },

        close() {
            this.setSheetCerKey(false);
            this.setDialogConfirStatus(false);
            this.$refs.form.reset();
        }
    },
}
</script>
