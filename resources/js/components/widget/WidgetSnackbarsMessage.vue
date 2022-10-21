<template>
<!-- multi-line -->
<v-snackbar v-model="vmodal_snackbar" :color="getSnackbarColorMessage" :timeout="getTimeout" :left="getLeft" :right="getRight" :top="getTop" :bottom="getBottom" >
    <strong color="white">{{ getSnackbarTextMessage }}</strong>

    <template v-slot:action="{ attrs }">
        <v-btn dark text color="white" small v-bind="attrs" @click="close()">
            <v-icon>fas fa-times-circle</v-icon>
        </v-btn>
    </template>
</v-snackbar>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex"

export default {
    props: [],
    components: {},
    data: () => ({
        vmodal_snackbar: false,
        timeout: 6000,
    }),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {
        ...mapGetters(['getSnackbarTextMessage', 'getSnackbarColorMessage', 'getSnackbarMessage', 'getLeft', 'getRight', 'getTop', 'getBottom', 'getTimeout']),
        ...mapState(['snackbar']),
    },
    watch: {
        getSnackbarMessage(val) {
            this.vmodal_snackbar = val;
        },
        vmodal_snackbar(val) {
            if (!val) {
                this.hideSnackbarMessage(false);
            }
        }
    },
    methods: {
        ...mapMutations(['hideSnackbarMessage']),
        
        close() {
            this.hideSnackbarMessage(false);
        }
    },
}
</script>
