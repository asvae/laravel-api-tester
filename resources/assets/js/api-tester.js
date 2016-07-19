import Vue from 'vue'
import vmApiTesterMain from './api-tester-main.vue'
import VueMdl from 'vue-mdl'

Vue.use(VueMdl)

new Vue({
    el: '#api-tester',
    components: {
        vmApiTesterMain
    },
})
