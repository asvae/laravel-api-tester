import _ from 'lodash'

const state = {
    errorLoading: false, // Can't load routes
    infoError: false, // Can't retrieve route info
    routes: [],
    currentRoute: null,
    isLoading: false,
}

const mutations = {
    SET_ROUTES: (state, routes) => {
        state.routes = routes
    },
    SET_ROUTES_ERROR(state, result){
        state.errorLoading = result
    },
    SET_INFO_ERROR: (state, error) => {
        state.infoError = error
    },
    SET_REQUEST_INFO: (state, route) => {
        if (route === null) {
            state.currentRoute = route
            return
        }
        state.currentRoute = _.find(state.routes, route)
    },
    SET_ROUTES_LOADING: (sate, isLoading) => {
        state.isLoading = isLoading
    }
}

export default {state, mutations}
