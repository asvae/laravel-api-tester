const state = {
    history: [],
}

const mutations = {
    /**
     * Load history from local storage
     * @param state
     * @constructor
     */
    LOAD_HISTORY(state){
        let history = window.localStorage.getItem('api-tester.history')
        try {
            history = JSON.parse(history)
            if (_.isNull(history)) history = []
        } catch (e) {
            history = []
        }
        state.history = history
    },
    SET_HISTORY(state, moment){
        state.history.push({
            method: moment.method,
            path : moment.path,
            body : moment.body,
            headers: moment.headers,
        })
        window.localStorage.setItem('api-tester.history', JSON.stringify(history))
    },
    CLEAR_HISTORY(state){
        window.localStorage.setItem('api-tester.history', '')
        state.history = []
    },
}

export default {state, mutations}
