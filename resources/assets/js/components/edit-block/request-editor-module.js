const state = {
        mode: 'data',
        isSending: false,
        scheduledList: [],
}

const mutations = {
    SET_EDITOR_MODE(state, mode){
        state.mode = mode
    },
    SET_REQUEST_IS_SENDING(state, value = true){
        state.isSending = value
    },
    SCHEDULE_REQUEST(state, request){
        state.scheduledList.push(request)
    },
    SHIFT_REQUEST(state){
        state.scheduledList.shift()
    },
}

export default {state, mutations,}
