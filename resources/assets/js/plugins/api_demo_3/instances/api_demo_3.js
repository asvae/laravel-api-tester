import vueDefault from 'vue'
import api from '../src/accessor'
import $ from 'jquery'

export default function (Vue){
    if (Vue === undefined){
        Vue = vueDefault
    }

    let api_demo = new Vue(api)
    api_demo.$options.root = '/api/'
    api_demo.$options.hold = [
        {url: 'users/listing'},
        {url: 'boards/listing'},
    ]
    api_demo.$options.cache = [
        {url: 'currencies/select-listing'}
    ]

    api_demo.$options.sync = []

    api_demo.$on('request-success', function (response) {
        if (response.meta && response.meta.message) {
            $.notify(response.meta.message, 'success')
        }
    })

    api_demo.$on('request-error', function (response) {
        // На некоторые запросы ответа не приходит
        if (!response) {
            return
        }

        if (!response.hasOwnProperty('responseJSON')) {
            if (response.status === 403){
                $.notify('Ошибка доступа: нет прав.')
                return
            }

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

    return api_demo
}