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
    this.$api.ajax('GET', 'requests')
        .then(function (response) {
            dispatch('SET_REQUESTS', response.data)
        })
}

export const setCurrentRequestFromRoute = ({dispatch}, route) => {
    let request = {
        method: route.methods[0],
        path: route.path,
        name: "",
        body: "",
        wheres: route.wheres,
        headers: [],
        config: {
            addCRSF: true,
        }
    }

    dispatch('SET_CURRENT_REQUEST', request)
}

export const setCurrentRequest = ({dispatch}, request) => {
    dispatch('SET_CURRENT_REQUEST', request)
}

export const scheduleRequest = function ({dispatch}, value) {
    dispatch('SET_SENDING_SCHEDULED', value)
}

export const deleteRequest = function ({dispatch}, request) {
    dispatch('DELETE_REQUEST', request)
    this.$api.ajax('DELETE', 'requests/' + request.id)
}

export const saveRequest = function ({dispatch}, request) {
    dispatch('SET_REQUEST_IS_SAVING', true)
    this.$api.ajax('POST', 'requests', request)
        .then(function (data) {
            dispatch('SET_REQUEST_IS_SAVING', false)
            dispatch('SET_CURRENT_REQUEST', data.data)
            next()
        })
}

export const updateRequest = function ({dispatch}, request) {
    dispatch('SET_REQUEST_IS_SAVING', true)
    this.$api.ajax('PUT', 'requests/' + request.id, request)
        .then(function (data) {
            dispatch('SET_REQUEST_IS_SAVING', false)
            dispatch('SET_CURRENT_REQUEST', data.data)
        })
}


