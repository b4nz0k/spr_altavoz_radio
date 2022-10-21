<template>
<v-app id="inspire">
    <v-navigation-drawer v-model="drawer" :clipped="$vuetify.breakpoint.lgAndUp" app>
        <v-list dense nav class="py-0">
            <v-list-item>
                <v-list-item-avatar>
                    <v-avatar color="primary" size="50">
                        <span class="white--text headline">
                            <strong>{{ infoUsuario.letter }}</strong>
                        </span>
                    </v-avatar>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>
                        <span class="body-2">
                            <strong>{{ infoUsuario.nombre }}</strong>
                        </span>
                    </v-list-item-title>
                    <v-list-item-subtitle>
                        <span>{{ infoUsuario.email }}</span>
                    </v-list-item-subtitle>
                    <v-list-item-subtitle>
                        <span>
                            <strong>{{ infoUsuario.estacion }}</strong>
                        </span>
                    </v-list-item-subtitle>

                </v-list-item-content>
            </v-list-item>
            <v-list-item>
                <v-list-item-content>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small text outlined color="primary" v-bind="attrs" v-on="on" @click="closeSesion()">Cerrar Sesión</v-btn>
                        </template>
                        <span>Cerrar Sesión</span>
                    </v-tooltip>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <v-divider></v-divider>
        <v-list dense>
            <template v-for="item in items">
                <v-row v-if="item.heading" :key="item.heading" align="center">
                    <v-col cols="6">
                        <v-subheader v-if="item.heading">
                            {{ item.heading }}
                        </v-subheader>
                    </v-col>
                    <v-col cols="6" class="text-center">
                        <a href="#!" class="body-2 black--text">EDIT</a>
                    </v-col>
                </v-row>
<!--                 <v-list-group v-else-if="item.children" :key="item.text" v-model="item.model" :prepend-icon="item.model ? item.icon : item['icon-alt']" append-icon="">
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item v-for="(child, i) in item.children" :key="i" link>
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <router-link :to="child.path" class="router-link">
                                <v-list-item-title>
                                    {{ child.text }}
                                </v-list-item-title>
                            </router-link>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group> -->
                <v-list-item v-else :key="item.text" link :to="item.path">
                    <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content  >
                        <router-link :to="item.path" class="router-link">
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </router-link>
                    </v-list-item-content>
                </v-list-item>

            </template>

            <v-list-item to="/usuarios" link v-if="user.nivel > 3">
                <v-list-item-action>
                    <v-icon> mdi-account-key  </v-icon>
                </v-list-item-action>

                <v-list-item-content  >
                        <v-list-item-title>
                                Usuarios
                        </v-list-item-title>
                    </v-list-item-content>
            </v-list-item>

            <v-list-item to="/estaciones" link v-if="user.nivel > 3">
                <v-list-item-action>
                    <v-icon> mdi-radio  </v-icon>
                </v-list-item-action>

                <v-list-item-content  >
                        <v-list-item-title>
                                Estaciones
                        </v-list-item-title>
                    </v-list-item-content>
            </v-list-item>

        </v-list>
    </v-navigation-drawer>

    <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="primary" dark>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title class="ml-0 mr-1 pa-0">
            <span class="hidden-sm-and-down">
                <router-link to='/' class="router-link m-auto pa-0">
                    <v-img :src="appLogo" max-width="120" width="120"></v-img>
                </router-link>
            </span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip
            class="ma-2"
            color="success"
            label
            text-color="white"
            >
                <v-icon left>
                    mdi-label
                </v-icon>
                <h2>
                    Estacion : {{infoUsuario.estacion}}
                </h2>
    </v-chip>
        <v-spacer></v-spacer>
                <v-menu
                bottom
                left
                offset-y
                origin="top right"
                transition="scale-transition"
            >
                <template v-slot:activator="{ on }">
                <v-btn dark icon v-on="on" class="mr-1">
                    <v-avatar color="secondary" size="50">
                        <span class="white--text headline">
                            <strong>{{ infoUsuario.letter }}</strong>
                        </span>
                    </v-avatar>
                </v-btn>
                </template>

                <v-list>
                <v-list-item to="/user"
                >
                    <v-list-item-title>Perfil</v-list-item-title>
                </v-list-item>
                <v-list-item
                    to="logout"
                >
                    <v-list-item-title>Cerrar Session</v-list-item-title>
                </v-list-item>
                </v-list>
            </v-menu>

    </v-app-bar>
    <v-main>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </v-main>
    <v-footer class="user-select">
        <v-row align="center" justify="center">
            <v-col cols="12" xl="12" justify="center" align="center" class="subtitle-2">
                <img :src="appFooter" class="img-responsive" width="160">
                <br>
                {{ new Date().getFullYear() }} —
                <strong>Sistema Público de Radiodifusión del Estado Mexicano (SPR)</strong>

                <br>
                <span class="caption">División de Tecnologías de la Información y Comunicaciones</span>
                <br />
            </v-col>
        </v-row>
    </v-footer>
    <widget-snackbars-message></widget-snackbars-message>
    <widget-sheets-loading></widget-sheets-loading>
    <widget-dialog-confirmacion></widget-dialog-confirmacion>
</v-app>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex";

import AppConfig from 'Constants/AppConfig';
import { Store } from "vuex";
import auth from "./store/modules/auth";
export default {
    props: {
        source: String,
    },
    components: {},
    data: () => ({
        dialog: false,
        drawer: null,
        appLogo: AppConfig.appLogo,
        appFooter: AppConfig.logoFooter,
        items: [
            {
                icon: "mdi-folder-multiple-image",
                text: "Banners",
                path: '/banner',
            },
            {
                icon: "mdi-label",
                text: "Categorías",
                path: '/categorias',
            },
            {
                icon: "mdi-format-list-bulleted",
                text: "Programas",
                path: '/programa',
            },
            {
                icon: "podcasts",
                text: "Potcast",
                path: '/podcast',
            },
            {
                icon: "mdi-account-details",
                text: "Autores",
                path: '/autores',
            },
            {
                icon: "photo_camera_front",
                text: "Productores",
                path: '/productores',
            },
/*              {
                icon: "mdi-chevron-up",
                "icon-alt": "mdi-chevron-down",
                text: "Programas",
                model: true,
                children: [
                    {
                        icon: "mdi-format-list-bulleted",
                        text: "Todos",
                        path: '/programa',
                    },
                    {
                        icon: "mdi-plus",
                        text: "Agregar Programa",
                        path: '/programa/agregar',
                    }
                ],
            },
       {
                 icon: "mdi-chevron-up",
                "icon-alt": "mdi-chevron-down",
                text: "Podcast",
                model: false,
                children: [
                    {
                        icon: "podcasts",
                        text: "Todos",
                        path: '/podcast',
                    },
                    {
                        icon: "mdi-plus",
                        text: "Agregar Podcast",
                        path: '/podcast/agregar',
                    }
                ],
            },
            {
                icon: "mdi-chevron-up",
                "icon-alt": "mdi-chevron-down",
                text: "Blog",
                model: false,
                children: [
                    {
                        icon: "mdi-post",
                        text: "Todos",
                        path: '/blog',
                    },
                    {
                        icon: "mdi-plus",
                        text: "Agregar Entrada",
                        path: '/blog/agregar',
                    }
                ],
            },
            {
                icon: "mdi-chevron-up",
                "icon-alt": "mdi-chevron-down",
                text: "Multimedia",
                model: false,
                children: [
                    {
                        icon: "mdi-youtube ",
                        text: "Todos",
                        path: '/multimedia',
                    },
                    {
                        icon: "mdi-plus",
                        text: "Agregar Video",
                        path: '/multimedia/agregar',
                    }
                ],
            }, */
            {
                icon: "mdi-format-list-checks ",
                text: "Programación",
                path: '/programacion',
            },
        ],
    }),
    beforeCreate() {},
    created() {
        this.loadUsuario();
    },
    mounted() {},
    computed: {
        ...mapGetters(['infoUsuario',]),
        ...mapState({
        user: ({ auth: { info_usuario } }) => info_usuario,
        }),
    },
    watch: {},
    methods: {
        loadUsuario() {
            this.$store.dispatch('logInUserInfo');
        },

        closeSesion() {
            this.$store.dispatch('singOff');

        },
    },
};
</script>

<style>
.user-select {
    user-select: none;
}

#inspire {
    overflow: hidden;
    width: 100vw;
}

.router-link {
    text-decoration: none;
    color: inherit;
}

.v-data-table-header {
    background-color: #27364d;
}

.theme--light.v-data-table thead tr th {
    color: white !important;
    text-transform: uppercase;
    background-color: #27364d;
}

.v-data-table-header>tr>th>i {
    color: #fff !important;
}

.v-data-table-header-mobile .theme--light.v-text-field>.v-input__control>.v-input__slot:before {
    border-color: rgba(255, 255, 255, 0.42);
}

.v-data-table-header-mobile .theme--light.v-text-field>.v-input__control>.v-input__slot:after {
    border-color: rgba(255, 255, 255, 0.42);
}

.v-data-table-header-mobile .v-icon.v-icon {
    color: #fff !important;
}

.v-data-table-header-mobile .v-select label {
    color: #fff !important;
}

thead .v-data-table__checkbox i.v-icon.notranslate.material-icons.theme--light {
    color: #fff !important;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease-in-out, transform 0.5s ease;
}

.fade-enter-active {
    transition-delay: 0.3s;
}

.fade-enter {
    opacity: 0;
    transform: translateY(100px);
}

.fade-enter-to {
    opacity: 1;
    transform: translateY(0px);
}

.fade-leave {
    opacity: 1;
    transform: translateY(0px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-100px);
}

.contextmenu {
    background-color: #283f63 !important;
}

.spinner {
    width: 42px;
    height: 40px;
    background-color: #4286da;
    margin: 31px auto;
    -webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
    animation: sk-rotateplane 1.2s infinite ease-in-out;
}

@-webkit-keyframes sk-rotateplane {
    0% {
        -webkit-transform: perspective(120px)
    }

    50% {
        -webkit-transform: perspective(120px) rotateY(180deg)
    }

    100% {
        -webkit-transform: perspective(120px) rotateY(180deg) rotateX(180deg)
    }
}

@keyframes sk-rotateplane {
    0% {
        transform: perspective(120px) rotateX(0deg) rotateY(0deg);
        -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
    }

    50% {
        transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
        -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
    }

    100% {
        transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
        -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
    }
}
</style>
