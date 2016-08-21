<template>
    <div class="routes-selector">
        <div class="columns is-multiline">
            <!-- Refresh route list -->
            <div class="column is-full">
                <a class="button"
                   @click="loadRoutes"
                >
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
            <!-- Route list -->
            <vm-route v-for="route in filteredRoutes"
                      class="column is-full"
                      transition="slip"
                      :route="route"
            ></vm-route>
            <!-- Placeholder -->
            <div class="column is-full" v-if="routes.length === 0">
                <div class="content">
                    <blockquote>No saved routes found</blockquote>
                </div>
            </div>
        </div>
        <div v-if="loadedWithError">
            <div class="notification is-danger">
                We can't retrieve route list from your app. Check console for details.
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
            return {}
        },
        vuex: {
            getters: {
                routes: store => store.routes.routes,
                loadedWithError: store => store.routes.errorLoading,
                search: store => store.search.search
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
            // TODO With vuex 2 this should be refactored as computed property.
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
