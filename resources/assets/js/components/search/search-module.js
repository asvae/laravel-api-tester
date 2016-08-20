const state = {
    search: '',
}

const mutations = {
    SET_SEARCH: (state, search) => {
        state.search = search
    },
}

export default {state, mutations}
