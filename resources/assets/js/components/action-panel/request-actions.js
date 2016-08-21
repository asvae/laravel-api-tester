export const saveRequest = function ({dispatch}, request, next = () => {}) {
    this.$api_demo2.load('requests/create', request)
        .then(function (data) {
            dispatch('SET_CURRENT_REQUEST', data.data)
            next()
        })
}

export const updateRequest = function ({dispatch}, request, next = () => {}) {
    this.$api_demo2.load('requests/update', request)
        .then(function (response) {
            dispatch('SET_CURRENT_REQUEST', response.data)
            dispatch('UPDATE_REQUEST', response.data)
            next()
        })
}