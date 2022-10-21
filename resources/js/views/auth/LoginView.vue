<template>
<v-container class="fill-height" fluid id="login-fluid">
    <v-progress-linear :active="loading" :indeterminate="loading" absolute top color="indigo darken-4 accent-4"></v-progress-linear>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="8" lg="8" xl="5" justify="center" align="center" class="elevation-10 pa-8" style="padding: 0px !important;">
            <v-row class="elevation-10" align="center" justify="center" style="background-color:#273652;">
                <v-col cols="12" md="6" lg="6" xl="6" justify="center" align="center" class="elevation-0 pa-8" style="background-color: #273552; height:100%;">
                    <img :src="appLogo" width="260" class="mb-10">
                    <br />
                    <img :src="facebook" width="30" />
                    <img :src="instagram" width="30" />
                    <img :src="twitter" width="30" />
                    <img :src="web" width="30" />
                    <img :src="youtube" width="30" />
                </v-col>
                <v-col cols="12" md="6" lg="6" xl="6" justify="center" align="center" class="elevation-0 pa-8 bg-white">
                    <img :src="appLogoCMS" class="img-responsive mb-4" width="200" />
                    <h2 class="mb-4">INICIAR SESIÓN</h2>
                    <v-form class="mb-5" v-on:submit.prevent>
                        <v-text-field v-model="vmodel_username" name="loginUsername" label="Usuario" placeholder prepend-icon="mdi-account" suffix="@spr.gob.mx" required :rules="emailRules" v-on:keyup="keypUpEmail"></v-text-field>
                        <v-text-field v-model="vmodel_password" name="loginPassword" label="Clave de acceso" placeholder prepend-icon="mdi-lock" :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'" :type="show3 ? 'text' : 'password'" :rules="passwordRules" @click:append="show3 = !show3" required @keyup.enter="clickLogin();"></v-text-field>
                        <div v-if="vmodel_username != '' && vmodel_password!=''">
                            <v-btn dark color="indigo" @click="clickLogin();">Iniciar Sesion</v-btn>
                        </div>
                        <div v-else>
                            <v-btn outlined disabled>Iniciar Sesion</v-btn>
                        </div>
                    </v-form>
                    <div class="py-4 mt-6">
                        <small>
                            {{ new Date().getFullYear() }} | División de Tecnologías de la Información y Comunicaciones
                            <br />
                            <strong>www.spr.gob.mx</strong>
                        </small>
                    </div>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
    <v-row align="center" justify="center">
        <v-col cols="12" lg="4"></v-col>
    </v-row>
    <widget-snackbars-message></widget-snackbars-message>
</v-container>
</template>

<script>
import AppConfig from 'Constants/AppConfig';
import {
    getValidateEmail
} from 'Helpers/helpers';

export default {
    props: [],
    components: {},
    data: () => ({
        appLogo: AppConfig.appLogin,
        facebook: AppConfig.facebook,
        instagram: AppConfig.instagram,
        twitter: AppConfig.twitter,
        web: AppConfig.web,
        youtube: AppConfig.youtube,
        appLogoCMS: AppConfig.cms,

        vmodel_username: "",
        vmodel_password: "",
        dialog: false,
        show3: false,
        passwordRules: [v => !!v || "Contraseña requerida"],
        emailRules: [
            v => !!v || "Usuario requerido",
            v => /^\w+[a-zA-Z0-9.]+$/.test(v) || "Usuario no valido"
        ],
        loading: false,
    }),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {},
    watch: {
        loading(val) {
            if (!val) return;

            setTimeout(() => {
                this.loading = false;
            }, 1000);
        }
    },
    methods: {
        keypUpEmail() {
            return this.vmodel_username = getValidateEmail(this.vmodel_username);
        },

        clickLogin() {
            if (this.vmodel_username != '' && this.vmodel_password != '') {
                this.loading = true;
                this.login();
            }
        },

        login() {
            let formLogin = new FormData();
            formLogin.append("email", this.vmodel_username + '@spr.gob.mx');
            formLogin.append("password", this.vmodel_password);
            axios.post("/acceso", formLogin).then(response => {
                window.location = "/home";
            }).catch(error => {
                if (error.response.status == 422) {
                    this.$store.dispatch('sanckbarsMessage', ['Estas credenciales no coinciden con nuestros registros.', 'error', true, '', ['top', 'right']]);
                } else if (error.response.status == 401) {
                    this.$store.dispatch('sanckbarsMessage', ['Error de autenticación, recargue la página por favor.', 'error', true, '', ['top', 'right']]);
                } else if (error.response.status == 419) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', ['top', 'right']]);
                } else if (error.response.status == 500) {
                    this.$store.dispatch('sanckbarsMessage', ['Error interno del servidor, recargue la página por favor.', 'error', true, '', ['top', 'right']]);
                } else {
                    this.$store.dispatch('sanckbarsMessage', ['Algo ha salido mal intentalo de nuevo.', 'error', true, '', ['top', 'right']]);
                }
                // axios.post('system/logs', {
                //     debug: 'Fronted',
                //     estatus: error.response.status,
                //     menssage: error.response.statusText,
                //     file: 'views\\auth\\LoginView.vue'
                // }).catch(error => {});
            });
        }
    },
}
</script>

<style>
.bg-white {
    background-color: #ffffff;
}
</style>
