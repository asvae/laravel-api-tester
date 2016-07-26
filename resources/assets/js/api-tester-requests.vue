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
                    <li v-for="request in requestsToDisplay"
                        transition="fade-in"
                        class="request"
                        :class="{selected: selected === request}"
                    >
                        <a>
                            <div class="columns">
                                <div class="column is-narrow">
                                    <button class="button is-small is-active"
                                            @click="setCurrentRequest(request), scheduleRequest(true)"
                                            v-text="request.method"
                                    ></button>
                                </div>
                                <a @click="setCurrentRequest(request)"
                                   class="column is-bold"
                                   v-text="request.path"
                                   style="white-space: nowrap"
                                ></a>
                                <a class="column is-narrow"
                                   v-text="'X'"
                                   @click.stop="deleteRequest(request)"
                                ></a>
                            </div>
                        </a>
                    </li>
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

    import * as actions from './vuex/actions.js'

    export default {
        data: function () {
            return {
                selected: null,
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
            },
            actions,
        },
        components: {
            vmSortOrderer
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

<style scoped>
    .request.selected {
        border-left: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }
</style>
