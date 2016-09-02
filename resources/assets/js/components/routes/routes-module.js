import _ from 'lodash'
import Vue from 'vue'
let vm = new Vue

export default {
    state: {
        search: '',
        errorLoading: false, // Can't load routes
        infoError: false, // Can't retrieve route info
        routes: [],
        currentRoute: null,
        isLoading: false,
    },
    mutations: {
        'set-routes': (state, routes) => {
            state.routes = routes
        },
        'set-routes-error'(state, result){
            state.errorLoading = result
        },
        'set-info-error': (state, error) => {
            state.infoError = error
        },
        'set-request-info': (state, route) => {
            if (route === null) {
                state.currentRoute = route
                return
            }
            state.currentRoute = _.find(state.routes, route)
        },
        'set-routes-loading': (state, isLoading) => {
            state.isLoading = isLoading
        },
    },
    getters: {
        filteredRoutes: state => {
            let toDisplay = []

            let search = state.search.toUpperCase()

            state.routes.forEach(function (route) {
                if (
                    route.methods.join(',').toUpperCase()
                         .includes(search)
                    || route.path.toUpperCase().includes(search)
                    || (route.action.controller && route.action.controller.toUpperCase()
                                                        .includes(search))
                    || (route.name && route.name.toUpperCase()
                                           .includes(search))
                ) {
                    toDisplay.push(route)
                }
            })

            return toDisplay
        }
    },
    actions: {
        setSearch ({commit}, search){
            commit('set-search', search)
        },
        loadRoutes ({commit}) {
            commit('set-routes-loading', true)
            commit('set-routes', [])

            console.log(this)

            vm.$api_demo2.load({url: 'routes/index'})
                .then((response) => {
                    commit('set-routes-error', false)
                    commit('set-routes', response.data)
                    commit('set-routes-loading', false)
                })
                .catch((xhr, status, error) => {
                    let response = {
                        status: xhr.status + ' : ' + error,
                        data: xhr.responseText
                    }
                    commit('SET_ROUTES_ERROR', response)
                    commit('set-routes-loading', false)
                })
        }
    }
}
