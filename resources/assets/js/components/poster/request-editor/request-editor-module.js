const state = {
        mode: 'data',
        isSending: false,
        isSaving: false,
        sendingIsScheduled: false,
}

const mutations = {
    SET_INFO_MODE(mode){
        state.infoMode = mode
    },
    SET_INFO_ERROR(state, response){
        state.infoError = response
    },
    
    SET_EDITOR_MODE(state, mode){
        state.mode = mode
    },
    SET_REQUEST_IS_SENDING(state, value = true){
        state.isSending = value
    },
    SET_REQUEST_IS_SAVING(state, value = true){
        state.isSaving = value
    },
    SET_SENDING_SCHEDULED(state, isScheduled = true){
        state.sendingIsScheduled = isScheduled
    },
}

export default {state, mutations,}
