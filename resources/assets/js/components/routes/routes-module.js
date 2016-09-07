import _ from 'lodash'
import Vue from 'vue'
let vm = new Vue

export default {
    state: {
        routes: [],
        currentRoute: null,
        errorLoading: false, // Can't load routes
    },
    mutations: {
        'set-routes': (state, routes) => {
            state.routes = routes
        },
        'set-error-loading'(state, result){
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
    },
    getters: {
        routes: state => state.routes,
        currentRoute: state => state.currentRoute,
        routesErrorLoading: state => state.errorLoading,
    },
    actions: {
        loadRoutes ({commit}) {
            vm.$api_demo2.load({url: 'routes/index'})
              .then(({data}) => {
                  commit('set-error-loading', false)
                  commit('set-routes', data)
              })
              .catch(({responseText}, status, error) => {
                  let response = {
                      status: status + ' : ' + error,
                      data: responseText
                  }
                  commit('set-error-loading', response)
              })
        },
        setCurrentRoute ({commit}, route) {
            commit('set-current-route', route)
        }
    }
}
