import './plugins/env.js'
import './plugins/globals.js'
import './vuex/vuex-installer.js'
import './plugins/api_demo/api-demo-installer.js'
import './plugins/api_demo_2/api-demo-installer.js'
import './plugins/api_demo_3/installer.js'

import Vue from 'vue'
import vmApiTesterMain from './api-tester.vue'
import store from './vuex/store.js'

new Vue({
    store,
    el: '#api-tester',
    components: {
        vmApiTesterMain
    },
})

