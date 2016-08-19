<template>
    <div class="routes-selector">
        <div class="columns is-multiline">
            <vm-route v-for="route in filteredRoutes"
                      class="column is-full"
                      transition="slip"
                      :route="route"
            ></vm-route>
            <div class="column is-full" v-if="routes.length === 0">
                <div class="content">
                    <blockquote>No saved routes found</blockquote>
                </div>
            </div>
        </div>
        <div v-if="routesError">
            <div class="notification is-danger">
                Error while getting route list
            </div>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'

    import vmRoute from './route.vue'

    import {loadRoutes} from '../../vuex/actions.js'

    export default {
        data () {
            return {
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
                routesError: (store) => store.routesError
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

                let search = this.search.toUpperCase()
                // Let's find out what to display first.
                this.routes.forEach(function (route) {

                    if (
                            route.methods.join(',').toUpperCase()
                                 .includes(search)
                            || route.path.toUpperCase().includes(search)
                            || (route.action.controller && route.action.controller.toUpperCase()
                                                                .includes(search))
                            || (route.name && route.name.toUpperCase()
                                                   .includes(search))
                    ) {
                        toDisplay.push(route)
                    }
                })

                return toDisplay
            }
        },
    }
</script>

<style scoped>
    .column {
        padding: 0 10px;
    }
</style>
