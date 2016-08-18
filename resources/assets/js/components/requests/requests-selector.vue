<template>
    <div class="requests-selector">
        <div class="columns is-multiline">
            <vm-request v-for="request in filteredRequests"
                        class="column is-full"
                        track-by="$index"
                        transition="slip"
                        :request="request"
            ></vm-request>
            <div class="column is-full"
                 v-if="requests.length === 0"
            >
                <div class="content">
                    <blockquote>No requests yet</blockquote>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'

    import {loadRequests, setRequests} from '../../vuex/actions.js'

    import vmRequest from './request.vue'

    export default {
        components: {
            vmRequest,
        },
        computed: {
            filteredRequests() {
                let toDisplay = []
                let search = this.search.toUpperCase()

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
        },
        ready (){
            // подписываемся на файрбейз
            if (ENV.firebaseToken && ENV.firebaseToken) {
                let source = new EventSource(ENV.firebaseSource + 'requests.json?auth=' + ENV.firebaseToken)
                source.addEventListener('put', ((e) => {
                    let payload = JSON.parse(e.data)
                    this.handlePayload(payload.data, payload.path)
                }))
            }

            this.loadRequests()
        },
        vuex: {
            getters: {
                requests: state => state.requests,
                currentRequest: state => state.currentRequest,
                search: state => state.search,
            },
            actions: {
                loadRequests,
                setRequests
            }
        },

        methods: {
            handlePayload(data, path){
                // Убираем слеш из пути, чтобы не мешался
                path = path.replace('/', '')

                let stateRequests = []

                // Если путь пуст, это значит что мы работаем с самой первой загрузкой,
                // И должны вставить все записи.
                if (path === '') {
                    let requests = data
                    for (let index in requests) {
                        this.prepareRequest(requests[index])
                        stateRequests.push(requests[index])
                    }
                }
                // Иначе, это пришло изменение, и нам нужо модифицировать сущестувующие записи.
                else {
                    stateRequests = _.cloneDeep(this.requests)
                    let request = data
                    // Если данные пусты, это значит что мы должны удалить запись из массива.
                    if (request === null) {
                        let index = _.findIndex(stateRequests, {id: path});
                        if(index !== -1) stateRequests.splice(1, index)

                    }
                    // Иначе нам нужно обновить её
                    else {
                        this.prepareRequest(request)
                        let index = _.findIndex(stateRequests, {id: path});
                        if(index !== -1) stateRequests.splice(1, index, request)
                    }
                }

                // Теперь нам нужно перезаписать состояние.
                this.setRequests(stateRequests)
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
