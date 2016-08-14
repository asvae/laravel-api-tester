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
        isSending: false,
        isSaving: false,
        sendingIsScheduled: false,
    },
    responseViewer:{
        mode: 'data',
        response: null,
    },
}

const mutations = {

    // Response viewer

    SET_RESPONSE(state, response){
        state.responseViewer.response = response
    },
    SET_VIEWER_MODE(state, mode){
        state.responseViewer.mode = mode
    },

    // Request editor

    SET_EDITOR_MODE(state, mode){
        state.requestEditor.mode = mode
    },
    SET_REQUEST_IS_SENDING(state, value = true){
        state.requestEditor.isSending = value
    },
    SET_REQUEST_IS_SAVING(state, value = true){
        state.requestEditor.isSaving = value
    },
    SET_SENDING_SCHEDULED(state, isScheduled = true){
        state.requestEditor.sendingIsScheduled = isScheduled
    },

    // Search

    SET_SEARCH: (state, search) => {
        state.search = search
    },

    // Requests

    SET_REQUESTS(state, requests){
        state.requests = requests
    },
    DELETE_REQUEST(state, request){
        state.requests.$remove(request)
    },
    SET_CURRENT_REQUEST(state, currentRequest){
        state.currentRequest = currentRequest
    },

    // Routes

    SET_ROUTES(state, routes){
        state.routes = routes
    },
    SET_CURRENT_ROUTE: (state, route) => {
        state.currentRoute = _.find(state.routes, route)
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