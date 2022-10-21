import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from 'Views/home/Home';
import PageError from 'Views/error/PageError';
import Perfil from 'Views/usuarios/Perfil.vue';
import Categoria from 'Views/categoria/Categoria';

import ProgramaAdd from 'Views/programa/ProgramaAgregar';
import ProgramaEdit from 'Views/programa/ProgramaEditar';
import Programas from 'Views/programa/Programas';

import PodcastAdd from 'Views/podcast/PodcastAgregar';
import PodcastEdit from 'Views/podcast/PodcastEditar';
import Podcast from 'Views/podcast/Podcast';

import BlogAdd from 'Views/blog/BlogAgregar';
import BlogEdit from 'Views/blog/BlogEditar';
import Blog from 'Views/blog/Blog';

import MultimediaAdd from 'Views/multimedia/MultimediaAgregar';
import MultimediaEdit from 'Views/multimedia/MultimediaEditar';
import Multimedia from 'Views/multimedia/Multimedia';

import Autores from 'Views/autor/autor';

import Productores from 'Views/productor/Productor'

import Programacion from 'Views/programacion/Programacion';
import Prog2 from 'Views/programacion/Programacion2';

// Divicion -----------
import Banner from 'Views/banner/Banner';

import UsuariosAdm from 'Views/usuarios/Usuarios'
import EstacionesAdm from 'Views/estaciones/Estaciones'

Vue.use(VueRouter)

const routes = [
    {
        path: '/home',
        component: Home,
    },
    {
        path: '/user',
        component: Perfil,
    },
    {
        path: '/',
        component: Home,
    },
    {
        path: '/page-error',
        component: PageError,
        name: 'page-error',
    },
    {
        path: '/banner',
        component: Banner,
        name: 'page-banner'
    },
    {
        path: '/categorias',
        component: Categoria,
        name: 'categoria',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/programa',
        component: Programas,
        name: 'programas',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/programa/agregar',
        component: ProgramaAdd,
        name: 'programaAdd',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/programa/editar/:token',
        component: ProgramaEdit,
        name: 'programaEdit',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/podcast',
        component: Podcast,
        name: 'podcast',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/podcast/agregar',
        component: PodcastAdd,
        name: 'podcastAdd',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/podcast/editar/:token',
        component: PodcastEdit,
        name: 'podcastEdit',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/blog',
        component: Blog,
        name: 'blog',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/blog/agregar',
        component: BlogAdd,
        name: 'blogAdd',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/blog/editar/:token',
        component: BlogEdit,
        name: 'blogEdit',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/multimedia',
        component: Multimedia,
        name: 'multimedia',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/multimedia/agregar',
        component: MultimediaAdd,
        name: 'multimediaAdd',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/multimedia/editar/:token',
        component: MultimediaEdit,
        name: 'multimediaEdit',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/programacion',
        component: Programacion,
        name: 'programacion',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/autores',
        component: Autores,
        name: 'autores',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/productores',
        component: Productores,
        name: 'productores',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/usuarios',
        component: UsuariosAdm,
        name: 'usuariosadm',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus && response.data.nivel >= 3) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
    {
        path: '/estaciones',
        component: EstacionesAdm,
        name: 'estacionesRadio',
        beforeEnter: (to, from, next) => {
            axios.get('usuario/info').then(response => {
                if (response.data.estatus && response.data.nivel >= 3) {
                    next();
                } else {
                    next('/page-error');
                }
            }).catch(error => {
                if (error.response.status == 401) {
                    console.log('Unauthorized');
                }
                if (error.response.status == 419) {
                    console.log('CFRC');
                }
                if (error.response.status == 500) {
                    console.log('Server Error');
                }
            });
        }
    },
]
const router = new VueRouter({
    // mode: 'history',
    base: process.env.BASE_URL,
    routes
})

// router.beforeEach((to, from, next) => {
//   document.title = to.meta.title(to)
//   next()
// })

export default router
