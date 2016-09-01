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
        <vm-route v-for="route in routes"
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
        ready (){
            this._blur()refresh()
        },
        methods: {
            refresh (){
                this.$store.dispatch('loadRoutes')
            }
        },
        computed: {
            routes (state) {
                return state.routes.routes
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
