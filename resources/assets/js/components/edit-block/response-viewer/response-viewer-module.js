export default {
    state: {
        mode: 'data',
        response: null,
    },
    mutations: {
        'set-response': (state, response) => state.response = response,
        'set-viewer-mode': (state, mode) => state.mode = mode,
    },
    getters: {
        responseViewerMode: state => state.mode,
        response: state => state.response,
    },
    actions: {
        setResponseViewerMode: ({commit}, mode) => commit('set-viewer-mode', mode),
        setResponse: ({commit}, response) => commit('set-response', response),
    }
}