import Vue from 'vue'
import _ from 'lodash'
import store from '../../../vuex/store.js'
import * as actions from './vuex/api-actions'

export default new Vue({
    data (){
        return {
            syncRoutes: [],
            warnRoutes: {},
        }
    },
    vuex: {
        getters: {
            urls: state => state.api.activeUrls
        },
        actions
    },
    methods: {
        isActive(url){
            // Might be undefined so we convert to boolean
            return this.urls[url] ? true : false
        },
        warn(route, confirm){
            let warn = {
                route,
                message: this.warnRoutes[route],
                confirm,
            }
            this.addUrlToWarn(warn)
        },
        requiresWarn(url){
            return this.warnRoutes[url] ? true : false
        },
        isSync(url){
            return _.includes(this.syncRoutes, url)
        },
        getEmptyPromise() {
            return {
                then: () => this,
                catch: () => this,
            }
        }
    },
    store
})