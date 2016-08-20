const state = {
    history: [],
}

const mutations = {
    SET_HISTORY(state, history){
        window.localStorage.setItem('api-tester.history', JSON.stringify(history))
        state.history = history
    },
    CLEAR_HISTORY(state){
        window.localStorage.setItem('api-tester.history', '')
        state.history = []
    },
}

export default {state, mutations}
