import _ from 'lodash'

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
        'set-routes-loading': (sate, isLoading) => {
            state.isLoading = isLoading
        },
        SET_SEARCH: (state, search) => {
            state.search = search
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
        loadRoutes ({commit}) {
            commit('set-routes-loading', true)
            commit('set-routes', [])

            this.$api_demo2.load({url: 'routes/index'})
                .then((response) => {
                    commit('SET_ROUTES_ERROR', false)
                    commit('SET_ROUTES', response.data)
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
