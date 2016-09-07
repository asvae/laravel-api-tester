import _ from 'lodash'



export const setResponse = ({dispatch}, response) => dispatch('SET_RESPONSE', response)

export const setRequests = function ({dispatch}, requests) {
    dispatch('SET_REQUESTS', requests)
}

export const setRequestInfo = ({dispatch}, route) => dispatch('SET_REQUEST_INFO', route)

export const scheduleRequest = ({dispatch}, request) => dispatch('SCHEDULE_REQUEST', _.cloneDeep(request))
