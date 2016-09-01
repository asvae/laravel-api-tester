import _ from 'lodash'

export const loadRequests = function ({dispatch}) {
    // TODO split Firebase and JSON storage.
    if (ENV.firebaseToken && ENV.firebaseToken) {
        return
    }

    this.$api_demo2.load({url: 'requests/index'})
        .then(function (response) {
            dispatch('SET_REQUESTS', response.data)
        })
}

export const setResponse = ({dispatch}, response) => dispatch('SET_RESPONSE', response)

export const setRequests = function ({dispatch}, requests) {
    dispatch('SET_REQUESTS', requests)
}

export const setRequestInfo = ({dispatch}, route) => dispatch('SET_REQUEST_INFO', route)

export const setCurrentRequest = ({dispatch}, request) => {
    dispatch('SET_CURRENT_REQUEST', request)
}

export const scheduleRequest = ({dispatch}, request) => dispatch('SCHEDULE_REQUEST', _.cloneDeep(request))
