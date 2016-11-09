// History if loaded from local storage.
let history = window.localStorage.getItem('api-tester.history')
try {
    history = JSON.parse(history)
    if (history === null) history = []
} catch (e) {
    history = []
}

export default {
    state: {
        history,
    },
    mutations: {
        'add-request-to-history': (state, request) => {
            state.history.push({
                method: request.method,
                path: request.path,
                body: request.body,
                headers: request.headers,
                createdAt: new Date().getTime(),
            })
            window.localStorage.setItem('api-tester.history', JSON.stringify(state.history))
        },
        'clear-history': (state) => {
            window.localStorage.setItem('api-tester.history', '')
            state.history = []
        },
    },
    getters: {
        history: state => state.history,
    },
    actions: {
        clearHistory: ({commit}) => commit('clear-history'),
        addMoment: ({commit}, request) => commit('add-request-to-history', request),
    }
}
