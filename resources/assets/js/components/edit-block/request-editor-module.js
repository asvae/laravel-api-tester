export default {
    state: {
        mode: 'data',
        isSending: false,
        scheduledList: [],
    },
    getters: {
        requestEditorMode: store => store.mode,
        requestEditorIsSending: store => store.isSending,
        requestEditorScheduledList: store => store.scheduledList,
    },
    mutations: {
        'set-editor-mode' (state, mode){
            state.mode = mode
        },
        'set-is-sending' (state, value = true){
            state.isSending = value
        },
        'schedule-request' (state, request){
            state.scheduledList.push(request)
        },
        'shift-request' (state){
            state.scheduledList.shift()
        },
    },
    actions: {
        setRequestEditorMode: ({commit}, mode) => commit('set-editor-mode', mode),
        setRequestEditorIsSending: ({commit}, isSending) => commit('set-is-sending', isSending),
        scheduleRequest: ({commit}, request) => commit('schedule-request', request),
        shiftRequest: ({commit}, request) => commit('shift-request', request),
    }
}
