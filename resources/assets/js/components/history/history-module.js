// History if loaded from local storage.
let history = window.localStorage.getItem('api-tester.history')
try {
    history = JSON.parse(history)
    if (history === null) history = []
} catch (e) {
    history = []
}

export default {
    state: {
        list: history,
    },
    mutations: {
        'set-history': (state, moment) => {
            state.list.push({
                method: moment.method,
                path : moment.path,
                body : moment.body,
                headers: moment.headers,
                createdAt: new Date().getTime(),
            })
            window.localStorage.setItem('api-tester.history', JSON.stringify(state.list))
        },
        'clear-history': (state) => {
            window.localStorage.setItem('api-tester.history', '')
            state.list = []
        },
    },
    getters: {
        filteredMoments: store => {
            let toDisplay = []

            let search = store.search.text.toUpperCase()
            // Let's find out what to display first.
            this.history.forEach(function (moment) {
                if (
                    moment.method.includes(search)
                    || moment.path.toUpperCase().includes(search)
                ) {
                    toDisplay.push(moment)
                }
            })

            return toDisplay.reverse()
        },
    },
}
