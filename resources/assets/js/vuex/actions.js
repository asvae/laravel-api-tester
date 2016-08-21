import _ from 'lodash'

export const loadRoutes = function ({dispatch}) {
    dispatch('SET_ROUTES_LOADING', true)
    dispatch('SET_ROUTES', [])

    this.$api_demo2.load({url: 'routes/index'})
        .then((response) => {
            dispatch('SET_ROUTES_ERROR', false)
            dispatch('SET_ROUTES', response.data)
            dispatch('SET_ROUTES_LOADING', false)
        })
        .catch((xhr, status, error) => {
            let response = {
                status : xhr.status + ' : ' + error,
                data : xhr.responseText
            }
            dispatch('SET_ROUTES_ERROR', response)
            dispatch('SET_ROUTES_LOADING', false)
        })
}

export const loadRequests = function ({dispatch}) {
    // TODO split Firebase and JSON storage.
    if (ENV.firebaseToken && ENV.firebaseToken) {
        return
    }

    this.$api_demo2.load('requests/index')
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
    dispatch('SET_REQUEST_INFO', route)
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

export const scheduleRequest = ({dispatch}, request) => dispatch('SCHEDULE_REQUEST', _.cloneDeep(request))
