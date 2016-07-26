import accessor from './src/Accessor.js'

import Api from './src/Api.js'
export default function (Vue, options) {
    Object.defineProperties(Vue.prototype, {
        $api: {
            get () {
                return new Api(this, options)
            }
        },
        $activeActions: {
            get () {
                return accessor.urls
            }
        }
    })
}


