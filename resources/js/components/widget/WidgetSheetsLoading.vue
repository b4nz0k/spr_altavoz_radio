<template>
<div class="text-center">
    <v-bottom-sheet v-model="vmodel_sheet" inset persistent>
        <v-sheet class="text-center" height="auto">
            <v-container>
                <v-row>
                    <v-col cols="12" lg="12">
                        <div class="spinner"></div>
                        <div class="my-0">
                            <strong>{{getSheetLoadingText}}</strong>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
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
    }),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {
        ...mapGetters(['getSheetLoading', 'getSheetLoadingText']),
    },
    watch: {
        getSheetLoading(val) {
            this.vmodel_sheet = val;
        },

        vmodel_sheet(val) {
            if (!val) {
                this.setShetLoading(false);
            }
        },
    },
    methods: {
        ...mapMutations(['setSheetLoading']),
    },
}
</script>
