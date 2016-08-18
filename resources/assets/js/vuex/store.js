import Vuex from 'vuex'
import _ from 'lodash'

const state = {
    history:[],
    routes: [],
    requests: [],
    currentRequest: {method: 'GET', path: '/', headers: [], body: ''},
    currentRoute: null,
    isRequestScheduled: false,
    search: '',
    infoMode: 'route',
    infoError: false,
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

    // History
    SET_HISTORY(state, history){
        window.localStorage.setItem('api-tester.history', JSON.stringify(history))
        state.history = history
    },
    CLEAR_HISTORY(state){
        window.localStorage.setItem('api-tester.history', '')
        state.history = []
    },

    // Response viewer

    SET_RESPONSE(state, response){
        state.responseViewer.response = response
    },

    SET_VIEWER_MODE(state, mode){
        state.responseViewer.mode = mode
    },

    // Request editor
    SET_INFO_MODE(mode){
        state.infoMode = mode
    },

    SET_INFO_ERROR(state, response){
        state.infoError = response
    },

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


    SET_CURRENT_REQUEST(state, currentRequest){
        state.currentRequest = currentRequest
    },

    SET_REQUESTS(state, requests){
        state.requests = requests
    },

    INSERT_REQUEST(state, request){
        let requests = _.cloneDeep(state.requests)
        requests.push(request)
        state.requests = requests
    },

    DELETE_REQUEST(state, request){
        let requests = _.cloneDeep(state.requests)
        let index = _.findIndex(requests, request);
        if(index !== -1) {
            requests.splice(index, 1)
            state.requests = requests
        }
    },

    UPDATE_REQUEST(state, request){
        let requests = _.cloneDeep(state.requests)
        let index = _.findIndex(requests, {id: request.id});
        if(index !== -1){
            requests.splice(index, 1, request)
            state.requests = requests
        }
    },
    // Routes

    SET_ROUTES(state, routes){
        state.routes = routes
    },

    SET_CURRENT_ROUTE: (state, route) => {
        if(route === null){
            return state.currentRoute = route
        }
        state.currentRoute = _.find(state.routes, route)
    },

    SET_REQUEST_SCHEDULED(state, isScheduled){
        state.isRequestScheduled = isScheduled
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