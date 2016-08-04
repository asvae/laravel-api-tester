import Vuex from 'vuex'

const state = {
    routes: [],
    requests: [],
    currentRequest: {method: 'GET', path: '/', headers: []},
    isRequestScheduled: false,
    search: '',
}

const mutations = {
    DELETE_REQUEST(state, request){
        state.requests.$remove(request)
    },
    SET_REQUESTS(state, requests){
        state.requests = requests
    },
    SET_ROUTES(state, routes){
        state.routes = routes
    },
    SET_CURRENT_REQUEST(state, currentRequest){
        state.currentRequest = currentRequest
    },
    SET_REQUEST_SCHEDULED(state, isScheduled){
        state.isRequestScheduled = isScheduled
    },
    SET_SEARCH: (state, search) => {
        state.search = search
    },
}

import api from '../plugins/api_demo/src/vuex/api-module.js'
export default new Vuex.Store({
    strict: true,
    state,
    mutations,
    modules: {
        // Plugins
        api
    }
})