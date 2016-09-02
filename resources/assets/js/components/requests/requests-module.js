import _ from 'lodash'

export default {
    state: {
        list: [],
        currentRequest: {method: 'GET', path: '/', headers: [], body: ''},
    },
    mutations: {
        'set-current-request': (state, currentRequest) => {
            state.currentRequest = currentRequest
        },
        'set-requests': (state, requests) => {
            state.requests = requests
        },
        'insert-request': (state, request) => {
            let requests = _.cloneDeep(state.requests)
            requests.push(request)
            state.requests = requests
        },
        'delete-request': (state, request) => {
            let requests = _.cloneDeep(state.requests)
            let index = _.findIndex(requests, request);
            if (index !== -1) {
                requests.splice(index, 1)
                state.requests = requests
            }
        },
        'update-request': (state, request) => {
            let requests = _.cloneDeep(state.requests)
            let index = _.findIndex(requests, {id: request.id});
            if (index !== -1) {
                requests.splice(index, 1, request)
                state.requests = requests
            }
        },
    },
    getters: {
        filteredRequests: state => {
            let toDisplay = []
            let search = state.search.text.toUpperCase()

            for (let request of this.requests) {
                if (
                    request.method.toUpperCase().includes(search)
                    || request.path.toUpperCase().includes(search)
                    || (request.name && request.name.toUpperCase()
                                               .includes(search))
                ) {
                    toDisplay.push(request)
                }
            }

            return toDisplay
        }
    }
}
