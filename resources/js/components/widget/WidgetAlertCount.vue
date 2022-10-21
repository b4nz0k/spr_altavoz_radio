<template>
<v-tooltip top>
    <template v-slot:activator="{ on, attrs }">
        <v-alert border="left" v-bind="attrs" v-on="on" colored-border :color="color" elevation="2" class="">
            <div style="position:relative;">
                <h1 class="text-right">
                    <countTo :startVal='0' :endVal='total' separator=',' :duration='4000'></countTo>
                </h1>
                <div style="position: absolute; top: -24px; left: -20px; z-index: 1;">
                    <v-chip class="ma-2" label :color="color" text-color="white">
                        {{textTitle}}
                    </v-chip>
                </div>
            </div>
        </v-alert>
    </template>
    <span>{{tooltip}}</span>
</v-tooltip>
</template>

<script>
import countTo from "vue-count-to";
export default {
    props: [
        'tooltip',
        'textTitle',
        'color',
        'total',
    ],
    components: {
        countTo
    },
    data: () => ({}),
    beforeCreate() {},
    created() {},
    mounted() {},
    computed: {},
    watch: {},
    methods: {},
}
</script>
