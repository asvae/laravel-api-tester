import Vue from 'vue'
import accessor from './src/ajax-manager.js'
import Api from './src/Api.js'

Vue.use(function (Vue, options) {
    Object.defineProperties(Vue.prototype, {
        $api_demo2: {
            get () {
                return new Api(this, options)
            }
        },
        $activeActions: {
            get: () =>  accessor.activeUrls
        },
        $accessor: {
            get: () => accessor
        },
    })
})