<template>
	<v-dialog v-model="vmodel_dialog" max-width="500" persistent>
		<v-card>
			<v-card-title class="headline">{{getDialogConfirTitulo}}</v-card-title>
			<v-card-text>{{getDialogConfirMensaje}}</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="primary" outlined @click="close()">Cancelar</v-btn>
				<v-btn color="error" outlined @click="confirmacion()">Si</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import {
    mapState,
    mapMutations,
    mapGetters
} from "vuex"

export default {
    props:[],
    components:{},
    data: () => ({
        vmodel_dialog: false,
    }),
    beforeCreate () {},
    created () {},
    mounted () {},
    computed: {
        ...mapGetters(['getDialogConfirTitulo', 'getDialogConfirMensaje', 'getDialogConfirDelete', 'getDialogConfirStatus']),
    },
    watch: {
        getDialogConfirDelete(val) {
            this.vmodel_dialog = val;
        },
        vmodel_dialog(val) {
            if (!val) {
                this.hiddenDialogConfirmDelete(false);
            }
        }
    },
    methods: {
        ...mapMutations(['hiddenDialogConfirmDelete', 'setDialogConfirStatus']),
        
        close() {
            this.hiddenDialogConfirmDelete(false);
            this.setDialogConfirStatus(false);
        },

        confirmacion() {
            this.setDialogConfirStatus(true);
            this.hiddenDialogConfirmDelete(false);
        },
    },
}
</script>
