<template>
    <div class="history-selector card is-fullwidth">
        <header class="card-header">
            <vm-search-panel class="card-header-title"
            ></vm-search-panel>
            <a class="button is-white is-large"
               v-if="history.length !== 0"
               @click="clearHistory"
            ><i class="fa fa-ban"></i></a>
        </header>
        <div class="notification"
             v-if="history.length === 0"
             transition="fade-in"
        >
            No history stored
        </div>
        <vm-moment v-for="moment in filteredMoments"
                   class="card-item"
                transition="slip"
                :moment="moment"
        ></vm-moment>
    </div>
</template>


<script>
    import _ from 'lodash'

    import vmMoment from './moment.vue'
    import vmSearchPanel from  '../search/search-panel.vue'

    export default {
        data () {
            return {}
        },
        components: {
            vmMoment,
            vmSearchPanel
        },
        vuex: {
            getters: {
                history: (store) => store.history.history,
                search: (store) => store.search.search,
            },
            actions: {
                setHistory: ({dispatch}, history) => {
                    dispatch('SET_HISTORY', history)
                },
                clearHistory: ({dispatch}) => {
                    dispatch('CLEAR_HISTORY')
                },
            },
        },
        computed: {
            filteredMoments () {
                let toDisplay = []

                let search = this.search.toUpperCase()
                // Let's find out what to display first.
                this.history.forEach(function (moment) {
                    if (
                            moment.method.includes(search)
                            || moment.path.toUpperCase().includes(search)
                    ) {
                        toDisplay.push(moment)
                    }
                })

                return toDisplay.reverse()
            },
        },
    }
</script>

<style scoped>
    .column {
        padding: 0;
    }
</style>
