export default {
    state: {
        infoError: false, // Can't retrieve route info
        info: null,
    },
    mutations: {
        'set-info-error': (state, error) => {
            state.infoError = error
        },
        'set-info': (state, info) => {
            state.info = info
        },
    },
    getters: {
        infoError: state => state.infoError,
        info: state => state.info,
    },
    actions: {
        setInfoError: ({commit}, error) => commit('set-info-error', error),
        setInfo: ({commit}, info) => commit('set-info', info),
    }
}
