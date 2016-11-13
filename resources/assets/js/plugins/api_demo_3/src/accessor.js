import _ from 'lodash'
import send from './adapters/jquery'
import libs from './libs/libs'
import proxyPromise from './libs/promiseProxy'

export default {

    // Synchronous route, second one will be blocked.
    // Useful for "Create" buttons and one time operations.
    // |1-----|
    // ^1   ^2
    sync: [],
    // Request will follow queue so that only one could run at
    // any given moment, yet the last scheduled response will always run.
    // Useful for live search etc, when race condition is inappropriate.
    // |1-----|2----|
    // ^1   ^2
    hold: [],
    // Request is executed only once. Every call gets the same promise.
    // |1-----|
    // ^1   ^2   ^3
    cache: [],
    // Responses route
    root: '',
    // Default headers
    headers: {},
    // Default request options
    request: {},
    // Auto manage xsrf token
    xsrf: true,

    data (){
        return {
            activeRequests: [],
            holds: [],
            holdenRequests: {},
            cachedResponses: {},
        }
    },
    methods: {
        /**
         * Boolean getters
         */
        runs(request){
            return _.some(this.activeRequests, request)
        },
        runsUrl(url){
            let request = {'url': url}
            return this.runs(request)
        },

        follows(rule, request){
            let predicate = (requestBare) => _.some([request], requestBare)
            return _.find(this.$options[rule], predicate)
        },


        /**
         * Core methods
         */

        post(path, data){
            let request = {
                method: 'post',
                url: path,
                data: data,
                headers: {}
            }

            return this.sendWithRules(request)
        },

        sendWithRules (request){
            let cacheRule = this.follows('cache', request)
            if (cacheRule) {
                return this.sendAndCacheRequest(request)
            }

            let holdRule = this.follows('hold', request)
            if (holdRule) {
                return this.sendAndHoldRequest(request, holdRule)
            }

            let syncRule = this.follows('sync', request)
            if (syncRule) {
                return this.sendAndSyncRequest(request, holdRule)
            }

            return this.send(request)
        },

        // Lowest level send method out here.
        send(cleanRequest){
            let dirtyRequest = this.createDirtyRequest(cleanRequest)

            // Send response and add listeners
            this.activeRequests.push(cleanRequest)
            console.log(dirtyRequest)
            let promise = send(dirtyRequest)
            promise.then((response) => this.$emit('request-success', response))
            promise.catch((xhr) => this.$emit('request-error', xhr))
            promise.always(() => {
                this.activeRequests.$remove(cleanRequest)
            })

            return promise
        },

        // Cache and return promise. Simple stuff.
        sendAndCacheRequest(request){
            let promise = this.cachedResponses[JSON.stringify(request)]

            if (! promise){
                promise = this.cachedResponses[JSON.stringify(request)] = this.send(request)
            }

            return promise
        },

        // Next response always waits for previous to finish.
        // Only last next response will be sent.
        sendAndHoldRequest(request, rule){

            // If this request is first for current rule
            // we'll add rule to holds and return promise.
            if (!this.runs(rule)) {
                this.holds.push(rule)

                return this.sendHolden(request, rule)
            }

            // If route is busy we'll store the response in closure and return
            // promise proxy.
            let promiseProxy = this.createPromiseProxy()
            this.holdenRequests[JSON.stringify(rule)] = () => {
                // When response is fulfilled this will apply
                // all the promises from proxy.
                let promise = this.sendHolden(request, rule)
                promiseProxy.applyTo(promise)
            }

            return promiseProxy
        },

        sendHolden(request, rule){
            let promise = this.send(request)

            this.holdenRequests[JSON.stringify(rule)] = () => {
                if (!this.runs(rule)){
                    this.holds.$remove(rule)
                }
            }

            //  On complete perform cleanup or send holden request.
            promise.then(() => {
                let next = this.holdenRequests[JSON.stringify(rule)]
                if(next){
                    next()
                }else{
                    this.holds.$remove(rule)
                }
            })

            return promise
        },

        sendAndSyncRequest(request, rule){
            // Response for busy route will be discarded.
            if (this.runs(rule)) {
                return this.createEmptyPromise()
            }
            return this.send(request)
        },

        createDirtyRequest(cleanRequest){
            let dirtyRequest = _.cloneDeep(cleanRequest)

            // Mutate request as needed.
            let defaultHeaders = this.createHeadersForMethod(dirtyRequest.method)
            _.defaults(dirtyRequest.headers, defaultHeaders)
            dirtyRequest.url = this.$options.root + dirtyRequest.url
            dirtyRequest.data = JSON.stringify(dirtyRequest.data)
            dirtyRequest.dataType = 'json'
            dirtyRequest.contentType = 'application/json; charset=utf-8'

            let defaultRequest = this.$options.request
            _.defaults(dirtyRequest, defaultRequest)

            return dirtyRequest
        },

        createHeadersForMethod(method) {
            let headers = _.clone(this.$options.headers)

            if (this.$options.xsrf) {
                // GET, HEAD and OPTIONS don't require token
                if (!_.some(['GET', 'HEAD', 'OPTIONS'], method.toUpperCase())) {
                    headers['X-XSRF-TOKEN'] = libs.getCookie('XSRF-TOKEN')
                }
            }

            return headers
        },

        createEmptyPromise() {
            return {
                then: () => this,
                catch: () => this,
            }
        },

        createPromiseProxy() {
            return proxyPromise()
        }


    }
}