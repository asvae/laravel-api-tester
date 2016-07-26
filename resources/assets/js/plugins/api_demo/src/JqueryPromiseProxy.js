import _ from 'lodash'

/**
 * This class works as Jquery proxy, meaning the application
 * won't notice any difference when you use it instead of original one.
 */
export default class JqueryPromiseProxy {

    constructor() {
        this._thens = []
        this._catches = []
    }

    then(callback) {
        this._thens.push(callback)
    }

    catch(callback) {
        this._catches.push(callback)
    }

    applyToJqueryPromise(promise) {
        _.each(this._thens, function (callback) {
            promise.then(callback)
        })
        _.each(this._catches, function (callback) {
            promise.catch(callback)
        })
    }
}