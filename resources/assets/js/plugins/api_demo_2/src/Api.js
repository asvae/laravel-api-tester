import _ from 'lodash'
import $ from 'jquery'
import accessor from './ajax-manager.js'
import JqueryPromiseProxy from './JqueryPromiseProxy'

// Busy route will discard new requests.
accessor.syncRoutes = []

// Route needs warning
accessor.warnRoutes = {
    'cashier/sales/delete': 'Message',
}

// This creates a response query so that only one could be running at
// any given moment, yet the last scheduled response will always run.
// Useful for live search and such. When race condition is inappropriate.
accessor.hold = [
    'boards/index'
]

export default class Api {
    constructor(vm, options) {
        this._vm = vm
        this._options = _.isObject(options) ? options : {}
    }

    /**
     * @param options string|object
     * @param data object
     * @returns {*}
     */
    load(options, data) {

        let route = typeof options === 'string' ? options : options.url

        // Response for busy route will be discarded.
        if (accessor.isActive(route) && accessor.isSync(route)) {
            return accessor.getEmptyPromise()
        }

        // Request requires notification.
        if (accessor.requiresWarn(route)) {
            let jqueryPromiseProxy = new JqueryPromiseProxy
            let promise = new Promise(function (resolve, reject) {
                accessor.warn(route, resolve)
            })

            promise.then(function () {
                let jqueryPromise = this.jqueryAjax(options, data)
                // Load real jquery promise with all thens and catches.
                jqueryPromiseProxy.applyToJqueryPromise(jqueryPromise)
            }.bind(this))

            return jqueryPromiseProxy
        }

        // Next response always waits for previous to finish.
        // Only last next response will be sent.
        if (accessor.requiresHold(route)) {
            if (accessor.isActive(route)) {
                let jqueryPromiseProxy = new JqueryPromiseProxy
                // If route is busy we'll store the response and return
                // promise proxy.
                accessor.holdenRoutes[route] = () => {
                    let jqueryPromise = this.load(options, data)
                    jqueryPromiseProxy.applyToJqueryPromise(jqueryPromise)
                }

                return jqueryPromiseProxy
            } else {
                // If route is free we'll send it right away.
                // Also we'll bind stored request on always.
                accessor.holdenRoutes[route] = () => {
                }
                let jqueryPromise = this.jqueryAjax(options, data)
                jqueryPromise.always(() => {
                    accessor.holdenRoutes[route]()
                    accessor.holdenRoutes[route] = () => {
                    }
                })
                return jqueryPromise
            }
        }

        return this.jqueryAjax(options, data)
    }

    /**
     * Call ajax to route.
     *
     * @param route
     * @param data
     * @returns {*}
     */
    jqueryAjax(options, data) {

        let route = typeof options === 'string' ? options : options.url
        let jqueryOptions = typeof options === 'string' ? {} : options

        let defaultOptions = {
            url: '/api/' + route,
            data: JSON.stringify(data),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': ENV.token
            },
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            context: this._vm,
        }

        // Merge options with defaults.
        jqueryOptions = _.defaults(jqueryOptions, defaultOptions)

        // This block handles detection of request start and end.
        let wasUndefined = accessor.activeUrls[route] === undefined
        // Mark the request start
        accessor.activeUrls[route] = true
        // Gonna clone the tree if route is called first time.
        if (wasUndefined) {
            accessor.activeUrls = _.clone(accessor.activeUrls)
        }

        return $.ajax(jqueryOptions)
                .done(function () {

                })
                .fail(function () {

                })
                .always(function () {
                    // Mark request ended.
                    accessor.activeUrls[route] = false
                })

    }
}