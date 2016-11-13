import _ from 'lodash'

export default function () {

    let thens = []
    let catches = []

    return {
        then(callback) {
            thens.push(callback)
        },
        catch(callback) {
            catches.push(callback)
        },
        applyTo(promise) {
            _.each(thens, function (callback) {
                promise.then(callback)
            })
            _.each(catches, function (callback) {
                promise.catch(callback)
            })
        }
    }
}