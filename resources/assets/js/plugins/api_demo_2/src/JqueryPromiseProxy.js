import _ from 'lodash'

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