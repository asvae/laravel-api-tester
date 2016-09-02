<template>
    <div class="history-selector card is-fullwidth">
        <header class="card-header">
            <vm-search-panel class="card-header-title"
            ></vm-search-panel>
            <a class="button is-white is-large"
               v-if="history.length !== 0"
               @click="$store.dispatch('clearHistory')"
            ><i class="fa fa-ban"></i></a>
        </header>
        <div class="notification"
             v-if="! $store.state.history.list.length"
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
    }
</script>

<style scoped>
    .column {
        padding: 0;
    }
</style>
