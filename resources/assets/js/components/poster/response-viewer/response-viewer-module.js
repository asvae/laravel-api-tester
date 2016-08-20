const state = {
    mode: 'data',
    response: null,
}

const mutations = {
    SET_RESPONSE(state, response){
        state.response = response
    },
    SET_VIEWER_MODE(state, mode){
        state.mode = mode
    },
}

export default {state, mutations}