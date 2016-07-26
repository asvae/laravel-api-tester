import _ from 'lodash'
import $ from 'jquery'
import accessor from './Accessor.js'
import JqueryPromiseProxy from './JqueryPromiseProxy'

// Double click protected routes.
accessor.syncRoutes = [
    // TODO remove to config file
    'some/route',
]

// Show notification for following routes.
accessor.warnRoutes = {
    // TODO remove to config file
    'some/route': 'Message to show',
}

export default class Api {
    constructor(vm, options) {
        this._vm = vm
        this._options = _.isObject(options) ? options : {}
    }

    /**
     * @param route string
     * @param data object
     * @returns {*}
     */
    load(route, data) {

        // Double click protection.
        if (accessor.isActive(route) && accessor.isSync(route)) {
            console.warn('You\'re clicking too fast...')
            return accessor.getEmptyPromise()
        }

        // Confirmation dialogue.
        if (accessor.requiresWarn(route)) {
            let jqueryPromiseProxy = new JqueryPromiseProxy
            let promise = new Promise(function (resolve, reject) {
                accessor.warn(route, resolve)
            })

            promise.then(function () {
                let jqueryPromise = this._jqueryAjax(route, data)
                // If request was confirmed, we'll apply all thens and
                // catches from proxy to our new jquery request.
                jqueryPromiseProxy.applyToJqueryPromise(jqueryPromise)
            }.bind(this))

            return jqueryPromiseProxy
        }

        return this._jqueryAjax(route, data)
    }

    /**
     * Make ajax request
     *
     * @param method
     * @param url
     * @param data
     * @param headers
     * @returns {*}
     */
    ajax(method, url, data = null, headers = []) {

        headers['X-CSRF-TOKEN'] = ENV.token

        return $.ajax({
            url,
            data,
            method,
            headers,
            context: this._vm,
        })
    }


    /**
     * Make simplified ajax request for route.
     *
     * @param route
     * @param data
     * @returns {*}
     */
    _jqueryAjax(route, data) {

        // Check request started.
        accessor.activateUrl(route)

        return $.ajax({
            url: '/api/' + route,
            data: JSON.stringify(data),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': ENV.token
            },
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            context: this._vm,
        })
                .done(function (response) {
                })
                .fail(function (response) {
                })
                .always(function () {
                    // Check request finished.
                    accessor.deactivateUrl(route)
                })
    }
}