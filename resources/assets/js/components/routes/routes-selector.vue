<template>
    <div class="routes-selector">
        <div class="box">

            <div class="tile is-ancestor">
                <!-- Routes -->
                <div class="tile is-vertical">
                    <vm-route v-for="route in filteredRoutes"
                              transition="slip"
                              :route="route"
                    ></vm-route>
                </div>
                <div class="tile" v-if="routes.length === 0">
                    Nothing found...
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'

    import vmRoute from './route.vue'

    import {loadRoutes} from '../../vuex/actions.js'

    export default {
        data () {
            return {
                selected: null,
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
                routes: (store) => store.routes,
                search: (store) => store.search,
            },
            actions: {loadRoutes},
        },
        ready (){
            this.loadRoutes()
        },
        components: {
            vmRoute,
        },
        computed: {
            filteredRoutes () {
                let toDisplay = []
                let sort = this.sort
                let asc = this.asc
                let search = this.search.toUpperCase()
                // Let's find out what to display first.
                this.routes.forEach(function (route) {
                    if (
                            route.methods.join(',').toUpperCase()
                                 .includes(search)
                            || route.path.toUpperCase().includes(search)
                            || route.action.toUpperCase().includes(search)
                            || (route.name && route.name.toUpperCase()
                                                   .includes(search))
                    ) {
                        toDisplay.push(route)
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
    .route.selected {
        border-left: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }
</style>
