<template>
    <div class="requests-selector card is-fullwidth">
        <header class="card-header">
            <vm-search-panel class="card-header-title"
            ></vm-search-panel>
        </header>
        <div class="notification"
             v-if="! $store.state.requests.list.length"
             transition="fade-in"
        >
            No requests yet
        </div>
            <vm-request v-for="request in $store.getters.filteredRequests"
                        class="is-fullwidth"
                        track-by="$index"
                        transition="slip"
                        :request="request"
            ></vm-request>
    </div>
</template>

<script>
    import _ from 'lodash'

    import {loadRequests, setRequests} from '../../vuex/actions.js'

    import vmRequest from './request.vue'
    import vmSearchPanel from  '../search/search-panel.vue'

    export default {
        components: {
            vmRequest,
            vmSearchPanel
        },
        computed: {

        },
        ready (){
            // TODO Move to actions or somewhere. Not the right place.
            // Subscribe to firebase
            if (ENV.firebaseToken && ENV.firebaseToken) {
                let source = new EventSource(ENV.firebaseSource + 'requests.json?auth=' + ENV.firebaseToken)
                source.addEventListener('put', ((e) => {
                    let payload = JSON.parse(e.data)
                    this.handlePayload(payload.data, payload.path)
                }))
            }

            this.$store.dispatch('loadRequests')
        },
        vuex: {
            actions: {
                loadRequests,
                setRequests,
                updateRequest: ({dispatch}, request) => {
                    dispatch('UPDATE_REQUEST', request)
                },
                deleteRequest: ({dispatch}, request) => {
                    dispatch('DELETE_REQUEST', request)
                },
                insertRequest: ({dispatch}, request) => {
                    dispatch('INSERT_REQUEST', request)
                },
            }
        },

        methods: {
            handlePayload(data, path){
                // Убираем слеш из пути, чтобы не мешался
                path = path.replace('/', '')

                // Если путь пуст, это значит что мы работаем с самой первой загрузкой,
                // И должны вставить все записи.
                if (path === '') {
                    let requests = []

                    for (let index in data) {
                        this.prepareRequest(data[index])
                        requests.push(data[index])
                    }

                    this.setRequests(requests)
                }
                // Иначе, это пришло изменение, и нам нужо модифицировать сущестувующие записи.
                else {
                    let request = data

                    // Если данные пусты, это значит что мы должны удалить запись из массива.
                    if (request === null) {
                        this.deleteRequest({id: path})
                    }
                    // Иначе нам нужно обновить её
                    else {
                        this.prepareRequest(request)
                        let index = _.findIndex(this.requests, {id: path})

                        if (index !== -1) {
                            this.updateRequest(request)
                        } else {
                            this.insertRequest(request)
                        }

                    }
                }
            },

            // Firebase не хранит пустые массивы. Поэтому заголовки придется добавить в ручную
            prepareRequest(request){
                if (request.headers === undefined) {
                    request.headers = []
                }
            }
        }
    }
</script>

<style scoped>
    .column {
        padding: 0 10px;
    }
</style>
