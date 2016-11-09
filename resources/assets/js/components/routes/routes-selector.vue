<template>
    <div class="routes-selector card is-fullwidth">
        <header class="card-header">
            <vm-search-panel class="card-header-title"></vm-search-panel>
            <a class="button is-large is-white is-fullheight"
               @click="refresh"
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
                  v-if="! $activeActions['routes/index']"
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

    export default {
        components: {
            vmRoute,
            vmSearchPanel,
        },
        ready (){
            this.refresh()
        },
        methods: {
            refresh (){
                this.$store.dispatch('loadRoutes')
            }
        },
        computed: {
            filteredRoutes (){
                let {search, routes} = this.$store.getters
                search = search.toUpperCase()
                let toDisplay = []

                routes.forEach(function (route) {
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
        }
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
