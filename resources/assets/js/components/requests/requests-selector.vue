<template>
    <div class="requests-selector">
        <div class="columns is-multiline">
            <div class="column">
                <div class="tile is-vertical">
                    <vm-request v-for="request in filteredRequests"
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

    import {loadRequests} from '../../vuex/actions.js'

    import vmRequest from './request.vue'

    export default {
        components: {
            vmRequest,
        },
        computed: {
            filteredRequests() {
                let toDisplay = []
                let search = this.search.toUpperCase()

                this.requests.forEach(function (request) {
                    if (
                            request.method.toUpperCase().includes(search)
                            || request.path.toUpperCase().includes(search)
                            || (request.name && request.name.toUpperCase()
                                                       .includes(search))
                    ) {
                        toDisplay.push(request)
                    }
                })

                return toDisplay
            }
        },
        ready (){
            this.loadRequests()
        },
        vuex: {
            getters: {
                requests: state => state.requests,
                currentRequest: state => state.currentRequest,
                search: state => state.search,
            },
            actions: {
                loadRequests
            }
        },
    }
</script>

<style>

</style>
