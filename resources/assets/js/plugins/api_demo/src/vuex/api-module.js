import _ from 'lodash'

const state = {
    activeUrls: {},
    urlsToWarn: [],
}

const mutations = {
    SET_ACTIVE_URL(state, url, value){
        // Cloning is required to trigger watcher.
        state.activeUrls[url] = value
        state.activeUrls = _.clone(state.activeUrls)
    },
    ADD_WARN(state, warn){
        state.urlsToWarn.push(warn)
    },
    REMOVE_WARN(state, warn){
        state.urlsToWarn.$remove(warn)
    }
}

export default {
    state,
    mutations
}