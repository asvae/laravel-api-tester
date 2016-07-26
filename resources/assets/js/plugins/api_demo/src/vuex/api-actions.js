export const activateUrl = function ({dispatch}, url) {
    dispatch('SET_ACTIVE_URL', url, true)
}

export const deactivateUrl = function ({dispatch}, url) {
    dispatch('SET_ACTIVE_URL', url, undefined)
}

export const addUrlToWarn = function ({dispatch}, warn) {
    dispatch('ADD_WARN', warn)
}

export const removeUrlToWarn = function ({dispatch}, warn) {
    dispatch('REMOVE_WARN', warn)
}