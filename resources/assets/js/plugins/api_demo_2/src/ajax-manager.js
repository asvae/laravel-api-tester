import Vue from 'vue'
import _ from 'lodash'

export default new Vue({
    data (){
        return {
            syncRoutes: [],

            // Routes to warn from config
            warnRoutes: {},
            // Current warned routes (closures)
            urlsToWarn: [],

            hold: [],
            holdenRoutes: {},

            activeUrls: {},
        }
    },
    methods: {
        isActive(url){
            // Might be undefined so we convert to boolean
            return this.activeUrls[url] ? true : false
        },
        warn(route, confirm){
            let warn = {
                route,
                message: this.warnRoutes[route],
                confirm,
            }
            this.urlsToWarn.push(warn)
        },
        requiresWarn(url){
            return this.warnRoutes[url] ? true : false
        },
        requiresHold(url){
            return _.includes(this.hold, url)
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
    }
})