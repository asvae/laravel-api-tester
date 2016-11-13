import Vue from 'vue'
import api from '../src/accessor'
import $ from 'jquery'

export default createInsance(Vue)

function createInsance(Vue) {
    let instance = new Vue(api)

    instance.$options.root = ENV.old_url + '/'
    instance.$options.hold = [
        // {url: 'users/listing'},
    ]
    instance.$options.cache = [
        // {url: 'currencies/select-listing'}
    ]

    instance.$options.sync = []

    instance.$options.headers = {

    }

    instance.$options.request = {
        crossDomain: true,
        xhrFields: {
            withCredentials: true
        },
    }
    instance.xsrf = false

    instance.$on('request-success', function (response) {
        if (response.meta && response.meta.message) {
            $.notify(response.meta.message, 'success')
        }
    })

    instance.$on('request-error', function (response) {
        // На некоторые запросы ответа не приходит
        if (!response) {
            return
        }

        if (!response.hasOwnProperty('responseJSON')) {
            $.notify('Непонятная ошибка')
            return
        }

        let json = response.responseJSON
        if (!(json.meta || json.meta.errors)) {
            return
        }

        json.meta.errors.forEach(function (error) {
            $.notify(error, 'error')
        })
    })

    return instance
}
