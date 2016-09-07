import _ from 'lodash'
import Vue from 'vue'
let vm = new Vue

export default {
    state: {
        requests: [],
        currentRequest: {method: 'GET', path: '/', headers: [], body: ''},
    },
    getters: {
        requests: state => state.requests,
        currentRequest: state => state.currentRequest,
    },
    mutations: {
        'set-current-request': (state, currentRequest) => {
            state.currentRequest = currentRequest
        },
        'set-requests': (state, requests) => {
            state.requests = requests
        },
        'insert-request': (state, request) => {
            let requests = _.cloneDeep(state.requests)
            requests.push(request)
            state.requests = requests
        },
        'delete-request': (state, request) => {
            let requests = _.cloneDeep(state.requests)
            let index = _.findIndex(requests, request);
            if (index !== -1) {
                requests.splice(index, 1)
                state.requests = requests
            }
        },
        'update-request': (state, request) => {
            let requests = _.cloneDeep(state.requests)
            let index = _.findIndex(requests, {id: request.id});
            if (index !== -1) {
                requests.splice(index, 1, request)
                state.requests = requests
            }
        },
    },
    actions: {
        loadRequests: ({commit}) => {
            // TODO split Firebase and JSON storage.
            if (ENV.firebaseToken && ENV.firebaseToken) {
                return
            }

            vm.$api_demo2.load({url: 'requests/index'})
              .then(({data}) => {
                  dispatch('set-requests', data)
              })
        },
        setCurrentRequest: ({commit}, request) => commit('set-current-request', request),
        setRequests: ({commit}, requests) => commit('set-requests', requests),
        insertRequest: ({commit}, request) => commit('insert-request', request),
        deleteRequest: ({commit}, request) => commit('delete-request', request),
        updateRequest: ({commit}, request) => commit('update-request', request),
    }
}
