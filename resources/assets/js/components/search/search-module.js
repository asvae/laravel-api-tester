// Search module

export default {
    state: {
        search: '',
    },
    getters: {
        search: state => state.search
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
