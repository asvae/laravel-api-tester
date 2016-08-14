<template>
    <div class="requests-selector">
        <div class="columns is-multiline">
            <div class="column">
                <div class="tile is-vertical">
                    <vm-request v-for="request of filteredRequests"
                                track-by="id"
                                transition="slip"
                                :request="request"
                    ></vm-request>
                </div>
                <div class="column" v-if="requests.length === 0">
                    Nothing found...
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

                for(let index in this.requests ){
                    let request = this.requests[index]
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
            if(ENV.firebaseToken !== '' && ENV.firebaseToken !== ''){
                let source = new EventSource(ENV.firebaseSource+'requests.json?auth='+ENV.firebaseToken)
                source.addEventListener('put', ((e) => {
                    let body = JSON.parse(e.data)
                    this.addRequest(body.data, body.path)
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

        methods:{
            addRequest(requests, path){
                path.replace('/', '')

                let stateRequests

                if(path === '/'){
                    stateRequests = requests
                    for(let index in requests){
                        this.prepareRequest(requests[index])
                    }
                } else {
                    stateRequests = _.cloneDeep(this.requests)
                    if(requests === null){
                        delete stateRequests[path];
                    }else {

                        this.prepareRequest(requests)
                        stateRequests[path] = requests
                    }
                }

                this.setRequests(stateRequests)
            },
            // Firebase не хранит пустые массивы. Поэтому заголовки придется добавить в ручную
            prepareRequest(request){
                if(request.headers === undefined){
                    request.headers = []
                }
            }
        }
    }
</script>

<style>

</style>
