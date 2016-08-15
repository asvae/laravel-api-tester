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
    if(ENV.firebaseToken !== '' && ENV.firebaseToken !== ''){
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
    dispatch('SET_CURRENT_REQUEST', request)
}

export const setCurrentRequestFromRoute = ({dispatch}, route) => {
    dispatch('SET_RESPONSE', null)

    let request = {
        method: route.methods[0],
        path: route.path,
        name: "",
        body: "",
        wheres: route.wheres,
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

export const saveRequest = function ({dispatch}, request) {
    dispatch('SET_REQUEST_IS_SAVING', true)
    this.$api.ajax('POST', 'requests', this.request)
        .then(function (data) {
            dispatch('SET_REQUEST_IS_SAVING', false)
            setCurrentRequest({dispatch}, data.data)
            this.loadRequests({dispatch})
        })
}

export const updateRequest = function ({dispatch}, request) {
    dispatch('SET_REQUEST_IS_SAVING', true)
    this.$api.ajax('PUT', 'requests/' + request.id, request)
        .then(function (data) {
            dispatch('SET_REQUEST_IS_SAVING', false)
            this.setCurrentRequest(data.data)
            this.loadRequests()
        })
}

export const scheduleRequest = ({dispatch}, status) => dispatch('SET_REQUEST_SCHEDULED', status)

export const scheduleSending = ({dispatch}, status) => dispatch('SET_SENDING_SCHEDULED', status)
