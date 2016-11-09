export default {
    state: {
        mode: 'data',
    },
    mutations: {
        'set-request-editor-mode': (state, mode) => state.mode = mode,
    },
    getters: {
        requestEditorMode: state => state.mode,
    },
    actions: {
        setRequestEditorMode: ({commit}, mode) => commit('set-request-editor-mode', mode),
    }
}