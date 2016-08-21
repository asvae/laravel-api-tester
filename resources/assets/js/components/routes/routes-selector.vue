<template>
    <!-- Wrapper: for escape fragmentation -->
    <div>
        <vm-card class="routes-selector is-fullwidth">
            <vm-search-panel
                    class="card-header-title"
                    slot="header"
            ></vm-search-panel>

            <a class="button is-large is-white is-fullheight"
               @click="loadRoutes"
               :class="{'is-loading' : isLoading}"
               slot="header"
            >
                <i class="fa fa-refresh" ></i>
            </a>

            <div class="notification"
                 v-if="routes.length !== 0 && filteredRoutes.length === 0"
                 transition="fade-in"
            >
                No routes matched.
            </div>

            <vm-route v-for="route in filteredRoutes"
                      class="is-fullwidth"
                      transition="slip"
                      :route="route"
            ></vm-route>

            <div class="notification"
                 v-if="routes.length === 0 && !loadedWithError"
                 transition="fade-in"
            >
                No routes found.
            </div><!-- /Placeholder -->

            <!-- Placeholder -->
            <div class="notification"
                 v-if="loadedWithError"
                 transition="fade-in"
            >
                We can't retrieve route list from your app. Check console for details.
            </div><!-- /Placeholder -->
        </vm-card><!-- /Card -->
    </div><!-- /Wrapper -->
</template>

<script>
    import _ from 'lodash'

    import vmRoute from './route.vue'
    import vmCard from '../ligth-components/card.vue'
    import vmSearchPanel from  '../search/search-panel.vue'
    import {loadRoutes} from '../../vuex/actions.js'

    export default {
        data () {
            return {}
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
        components: {
            vmRoute, vmCard, vmSearchPanel
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
