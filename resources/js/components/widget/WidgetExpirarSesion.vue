<template>

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
    data: () => ({}),
    beforeCreate() {},
    created() {},
    mounted() {
        var moviendo = false;
        var id_user = '';
        document.onmousemove = function () {
            moviendo = true;
        };

        var interval = setInterval(function () {
            if (!moviendo) {
                axios.get('sesion/token').then(response => {
                    // console.log(response.data);
                    if (response.data['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'] == null) {
                        id_user = '';
                    } else {
                        id_user = response.data['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];
                    }
                    if (id_user == '') {
                        clearInterval(interval);
                    } else {
                        axios.post('sesion/expired', {
                            user: id_user
                        }).then(response => {
                            localStorage.clear();
                            window.location = "/";
                        });
                        clearInterval(interval);
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
                        file: 'components\\widget\\WidgetExpirarSesion.vue'
                    }).catch(error => {});
                });
            } else {
                moviendo = false;
            }
        }, 1800000);
        // 30 minutos - milisegundos
    },
    computed: {},
    watch: {},
    methods: {},
}
</script>
