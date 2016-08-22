// History if loaded from local storage.
let history = window.localStorage.getItem('api-tester.history')
try {
    history = JSON.parse(history)
    if (history === null) history = []
} catch (e) {
    history = []
}

const state = {
    history,
}

const mutations = {
    SET_HISTORY(state, moment){
        state.history.push({
            method: moment.method,
            path : moment.path,
            body : moment.body,
            headers: moment.headers,
            createdAt: new Date().getTime(),
        })
        window.localStorage.setItem('api-tester.history', JSON.stringify(history))
    },
    CLEAR_HISTORY(state){
        window.localStorage.setItem('api-tester.history', '')
        state.history = []
    },
}

export default {state, mutations}
