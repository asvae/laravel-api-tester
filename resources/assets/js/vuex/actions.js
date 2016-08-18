import _ from 'lodash'
import RandExp from 'randexp'

export const loadRoutes = function ({dispatch}) {
    this.$api.ajax('GET', 'routes')
        .then(function (response) {
            let routes = response.data
            let order = 0

            let parsedRoutes = _.map(routes, function (route) {
                route.type = 'route'
                route.order = order++
                return route
            })

            dispatch('SET_ROUTES', parsedRoutes)
        })
}

export const loadRequests = function ({dispatch}) {
    // TODO split Firebase and JSON storage.
    if(ENV.firebaseToken && ENV.firebaseToken){
        return
    }

    this.$api.ajax('GET', 'requests')
        .then(function (response) {
            dispatch('SET_REQUESTS', response.data)
        })
}

export const setRequests = function ({dispatch}, requests) {
    dispatch('SET_REQUESTS', requests)
}

export const setCurrentRequest = ({dispatch}, request) => {
    dispatch('SET_INFO_MODE', 'request')
    dispatch('SET_CURRENT_ROUTE', null)
    dispatch('SET_CURRENT_REQUEST', request)
}

export const setCurrentRequestFromRoute = ({dispatch}, route) => {
    dispatch('SET_INFO_MODE', 'route')
    dispatch('SET_RESPONSE', null)
    dispatch('SET_CURRENT_ROUTE', route)
    let request = {
        method: route.methods[0],
        path: route.path,
        name: "",
        body: route.hasOwnProperty('body') ? route.body : "",
        wheres: route.hasOwnProperty('wheres') ? route.wheres : {},
        headers: route.hasOwnProperty('headers') ? route.headers : [],
        config: {
            addCRSF: true,
        }
    }

    dispatch('SET_CURRENT_REQUEST', request)
}

export const deleteRequest = function ({dispatch}, request) {
    dispatch('DELETE_REQUEST', request)
    this.$api.ajax('DELETE', 'requests/' + request.id)
}

export const saveRequest = function ({dispatch}, request, next = () => {}) {
    dispatch('SET_REQUEST_IS_SAVING', true)
    this.$api.ajax('POST', 'requests', this.request)
        .then(function (data) {
            dispatch('SET_REQUEST_IS_SAVING', false)
            dispatch('SET_CURRENT_REQUEST', data.data)
            dispatch('SET_CURRENT_REQUEST', data.data)
            next()
        })
}

export const updateRequest = function ({dispatch}, request, next = () => {}) {
    dispatch('SET_REQUEST_IS_SAVING', true)
    this.$api.ajax('PUT', 'requests/' + request.id, request)
        .done(function (response) {
            dispatch('SET_REQUEST_IS_SAVING', false)
            dispatch('SET_CURRENT_REQUEST', response.data)
            dispatch('UPDATE_REQUEST', response.data)
        })
}

export const scheduleSending = ({dispatch}, status) => dispatch('SET_SENDING_SCHEDULED', status)
