import _ from 'lodash'

const state = {
    errorLoading: false,
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
    SET_CURRENT_ROUTE: (state, route) => {
        if(route === null){
            return state.currentRoute = route
        }
        state.currentRoute = _.find(state.routes, route)
    },

    SET_ROUTES_LOADING: (sate, isLoading) => {
        state.isLoading = isLoading
    }
}

export default {state, mutations}
