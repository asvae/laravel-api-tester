import _ from 'lodash'
import Vue from 'vue'
let vm = new Vue

function prepareRequest(request) {
    request = _.cloneDeep(request)
    request.body = JSON.stringify(request.body)

    console.log(request)

    return request
}

export default {
    state: {
        requests: [],
        currentRequest: {method: 'GET', path: '/', headers: [], body: "{}"},
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
            let requests = state.requests
            requests.push(request)
        },
        'delete-request': (state, request) => {
            let requests = state.requests
            let index = _.findIndex(requests, request);
            if (index !== -1) {
                requests.splice(index, 1)
            }
        },
        'update-request': (state, request) => {
            let requests = state.requests
            let index = _.findIndex(requests, {id: request.id});
            if (index !== -1) {
                requests.splice(index, 1, request)
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
                  commit('set-requests', data)
              })
        },
        deleteRequest: ({commit}, request) => {
            vm.$api_demo2.load({url: 'requests/destroy'}, request)
              .then(() => {
                  commit('delete-request', request)
              })
        },

        setCurrentRequest: ({commit}, request) => {
            commit('set-current-request', request)
        },

        setRequests: ({commit}, requests) => commit('set-requests', requests),

        // TODO This relates to firebase and should be moved to somewhere.
        // insertRequest: ({commit}, request) => commit('insert-request', request),
        // deleteRequest: ({commit}, request) => commit('delete-request', request),
        // amendRequest: ({commit}, request) => commit('update-request', request),

        saveRequest: ({commit}, request) => {
            vm.$api_demo2.load({url: 'requests/store'}, prepareRequest(request))
              .then(({data}) => {
                  commit('set-current-request', data)
                  commit('insert-request', data)
              })
        },
        updateRequest: ({commit}, request) => {

            vm.$api_demo2.load({url: 'requests/update'}, prepareRequest(request))
              .then(({data}) => {
                  commit('set-current-request', data)
                  commit('update-request', data)
              })
        },
    }
}
