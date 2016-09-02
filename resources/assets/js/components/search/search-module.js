// Search module

export default {
    state: {
        text: '',
    },
    mutations: {
        'set-search': (state, search) => {
            state.search = search
        },
    },
    actions: {
        setSearch ({commit}, search){
            commit('set-search', search)
        },
    }
}
