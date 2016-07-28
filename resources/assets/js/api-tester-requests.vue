<template>
    <div class="api-tester-requests">

        <!-- Search -->
        <form action="#" @submit.prevent class="box">
            <input class="input"
                   type="text"
                   placeholder="Search"
                   title="Search"
                   v-model="search"
            >
        </form>

        <div class="box">

            <!-- Sort order -->
            <div class="columns">
                <a v-for="column in columns"
                   class="column"
                   @click="setSorting(column)"
                   style="width: 50px"
                >
                    {{ column | uppercase }}
                    <vm-sort-orderer :value="asc"
                                     v-if="sort === column"
                                     @change="asc = $arguments[0]"
                    ></vm-sort-orderer>
                </a>
            </div>

            <aside class="menu">
                <ul class="menu-list">
                    <vm-request v-for="request in requestsToDisplay"
                                :request="request"
                    ></vm-request>
                </ul>
            </aside>

            <div v-if="! requestsToDisplay.length">
                <div>
                    <div>
                        Nothing found...
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'
    import vmSortOrderer from './sort-orderer.vue'
    import vmRequest from './request.vue'

    import {loadRequests} from './vuex/actions.js'

    export default {
        data: function () {
            return {
                search: '',
                sort: null,
                asc: true,
                columns: [
                    'method',
                    'path',
                ],
            }
        },
        vuex: {
            getters: {
                requests: state => state.requests,
                currentRequest: state => state.currentRequest,
            },
            actions: {
                loadRequests
            }
        },
        components: {
            vmRequest,
            vmSortOrderer,
        },
        ready (){
            this.loadRequests()
        },
        computed: {
            requestsToDisplay () {
                let toDisplay = []
                let sort = this.sort
                let asc = this.asc
                let search = this.search.toUpperCase()
                // Let's find out what to display first.
                this.requests.forEach(function (request) {
                    if (
                            request.method.toUpperCase().includes(search)
                            || request.path.toUpperCase().includes(search)
                    ) {
                        toDisplay.push(request)
                    }
                })
                // Then sort it.
                if (this.sort) {
                    toDisplay = toDisplay.sort(function (a, b) {
                        let comparingA = a[sort]
                        let comparingB = b[sort]

                        if (Array.isArray(comparingA)) {
                            comparingA = comparingA.join(',')
                        }
                        if (Array.isArray(comparingB)) {
                            comparingB = comparingB.join(',')
                        }

                        return comparingA.localeCompare(comparingB) * (asc ? -1 : 1)
                    })
                }
                return toDisplay
            }
        },
        methods: {
            setSorting(param){
                if (param === this.sort) {
                    // Click on same column
                    this.asc = !this.asc
                    return
                } else {
                    // Click on different column
                    this.asc = true
                    this.sort = param
                }
            }
        }
    }
</script>

<style>

</style>
