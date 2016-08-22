<template>
    <div class="routes-selector card is-fullwidth">
        <header class="card-header">
            <vm-search-panel class="card-header-title"></vm-search-panel>
            <a class="button is-large is-white is-fullheight"
               @click="loadRoutes"
               :class="{'is-loading' : isLoading}"
            >
                <i class="fa fa-refresh"></i>
            </a>
        </header>
            <div class="notification"
                 v-if="filteredRoutes.length === 0 && ! isLoading"
                 transition="fade-in"
            >
                No routes matched.
            </div>
            <vm-route v-for="route in filteredRoutes"
                      class="is-fullwidth"
                      transition="slip"
                      :route="route"
            ></vm-route>
            <div class="notification is-danger"
                 v-if="loadedWithError"
                 transition="fade-in"
            >
                Can't load routes. Check console for details.
            </div>
    </div>
</template>

<script>
    import _ from 'lodash'

    import vmRoute from './route.vue'
    import vmSearchPanel from  '../search/search-panel.vue'
    import {loadRoutes} from '../../vuex/actions.js'

    export default {
        data () {
            return {}
        },
        components: {
            vmRoute,
            vmSearchPanel,
        },
        vuex: {
            getters: {
                routes: store => store.routes.routes,
                isLoading: store => store.routes.isLoading,
                loadedWithError: store => store.routes.errorLoading,
                search: store => store.search.search
            },
            actions: {loadRoutes},
        },
        ready (){
            this.loadRoutes()
        },
        computed: {
            // TODO With vuex 2 this should be refactored as computed property.
            filteredRoutes () {
                let toDisplay = []

                let search = this.search.toUpperCase()

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

    .notification {
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }
</style>
