<template>
    <div class="requests-selector card is-fullwidth">
        <header class="card-header">
            <vm-search-panel class="card-header-title"
            ></vm-search-panel>
        </header>
        <div class="notification"
             v-if="! filteredRequests.length"
             transition="fade-in"
        >
            No requests found
        </div>
        <vm-request v-for="request in filteredRequests"
                    class="is-fullwidth"
                    track-by="$index"
                    transition="slip"
                    :request="request"
        ></vm-request>
    </div>
</template>

<script>
    import _ from 'lodash'

    import vmRequest from './request.vue'
    import vmSearchPanel from  '../search/search-panel.vue'

    export default {
        components: {
            vmRequest,
            vmSearchPanel
        },
        computed: {},
        ready (){
            // TODO Move to actions or somewhere. Not the right place.
            // Subscribe to firebase
            if (ENV.firebaseToken && ENV.firebaseToken) {
                let source = new EventSource(ENV.firebaseSource + 'requests.json?auth=' + ENV.firebaseToken)
                source.addEventListener('put', ((e) => {
                    let payload = JSON.parse(e.data)
                    console.log(payload)
                    this.handlePayload(payload.data, payload.path)
                }))
            }

            this.$store.dispatch('loadRequests')
        },
        computed: {
            filteredRequests (){
                let {search, requests} = this.$store.getters
                search = search.toUpperCase()
                let toDisplay = []

                requests.forEach(function (request) {
                    let isSelected = request.method.toUpperCase()
                                            .includes(search)
                            || request.path.toUpperCase().includes(search)
                            || (request.name && request.name.toUpperCase()
                                                       .includes(search))

                    isSelected && toDisplay.push(request)
                })

                return toDisplay
            }
        },
        methods: {
            handlePayload(data, path){
                // Remove slash from the path, so it won't mess up the request.
                path = path.replace('/', '')

                // TODO Test the thing and comment properly
                // Если путь пуст, это значит что мы работаем с самой первой загрузкой,
                // И должны вставить все записи.
                if (path === '') {
                    let requests = []

                    for (let index in data) {
                        this.prepareRequest(data[index])
                        requests.push(data[index])
                    }

                    this.$store.dispatch('setRequests', requests)
                    return
                }

                // Not empty path means change came by, so we have to update existing entries.
                let request = data

                // Null means we have to delete request from array
                if (request === null) {
                    this.$store.dispatch('deleteRequest', {id: path})
                    return
                }

                // Otherwise we refresh the entry.
                this.prepareRequest(request)
                let index = _.findIndex(this.requests, {id: path})

                if (index !== -1) {
                    this.$store.dispatch('updateRequest', request)
                } else {
                    this.$store.dispatch('insertRequest', request)
                }
            }
        },

        // Firebase won't store empty arrays, but we need them in app.
        prepareRequest(request){
            if (request.headers === undefined) {
                request.headers = []
            }
        }
    }
</script>

<style scoped>
    .column {
        padding: 0 10px;
    }
</style>
