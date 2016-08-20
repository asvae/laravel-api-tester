import _ from 'lodash'

const state = {
    requests: [],
    currentRequest: {method: 'GET', path: '/', headers: [], body: ''},
}

const mutations = {
    SET_CURRENT_REQUEST(state, currentRequest){
        state.currentRequest = currentRequest
    },
    SET_REQUESTS(state, requests){
        state.requests = requests
    },
    INSERT_REQUEST: (state, request) => {
        let requests = _.cloneDeep(state.requests)
        requests.push(request)
        state.requests = requests
    },
    DELETE_REQUEST: (state, request) => {
        let requests = _.cloneDeep(state.requests)
        let index = _.findIndex(requests, request);
        if(index !== -1) {
            requests.splice(index, 1)
            state.requests = requests
        }
    },
    UPDATE_REQUEST: (state, request) => {
        let requests = _.cloneDeep(state.requests)
        let index = _.findIndex(requests, {id: request.id});
        if(index !== -1){
            requests.splice(index, 1, request)
            state.requests = requests
        }
    },
}

export default {state, mutations}
