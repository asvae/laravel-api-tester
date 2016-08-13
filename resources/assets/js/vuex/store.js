import Vuex from 'vuex'
import _ from 'lodash'

const state = {
    routes: [],
    requests: [],
    currentRequest: {method: 'GET', path: '/', headers: []},
    currentRoute: null,
    isRequestScheduled: false,
    search: '',
    requestEditor:{
        mode: 'data',
        saving: false,
    },
    responseViewer:{
        mode: 'data',
        processing: false,
    },
}

const mutations = {
    SET_EDITOR_MODE(state, mode){
        state.requestEditor.mode = mode
    },
    SET_VIEWER_MODE(state, mode){
        state.responseViewer.mode = mode
    },
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
    SET_CURRENT_ROUTE: (state, route) => {
        state.currentRoute = _.find(state.routes, route)
    },
    REQUEST_IS_SAVING: (state, value) => {
        state.requestEditor.saving = value
    }
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