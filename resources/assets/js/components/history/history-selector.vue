<template>
    <!-- Wrapper: for escape fragmentation -->
    <div>
        <vm-card class="history-selector">
            <vm-search-panel
                class="card-header-title"
                slot="header"
            ></vm-search-panel>

            <a class="button is-white is-large"
               v-if="history.length !== 0"
               @click="clearHistory"
               slot="header"
            ><i class="fa fa-trash"></i></a>

            <div class="notification"
                 v-if="history.length === 0"
                 transition="fade-in"
            >
                No history stored
            </div>
            <vm-moment
                v-for="moment in filteredMoments"
                class="column is-full"
                transition="slip"
                :moment="moment"
            ></vm-moment>
        </vm-card>
    </div>
</template>


<script>
    import _ from 'lodash'

    import vmMoment from './moment.vue'
    import vmCard from '../ligth-components/card.vue'
    import vmSearchPanel from  '../search/search-panel.vue'

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
                history: (store) => store.history.history,
                search: (store) => store.search.search,
                isSending: state => state.request.isSending,
                currentRequest: state => state.requests.currentRequest,

            },
            actions: {
                setHistory: ({dispatch}, history) => {
                    dispatch('SET_HISTORY', history)
                },
                clearHistory: ({dispatch}) => {
                    dispatch('CLEAR_HISTORY')
                }
            },
        },
        ready (){
            this.loadHistory()
        },
        components: {
            vmMoment, vmCard, vmSearchPanel
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
        methods: {
            loadHistory(){
                let history = window.localStorage.getItem('api-tester.history')

                try {
                    history = JSON.parse(history)
                    if (_.isNull(history)) history = []
                } catch (e) {
                    history = []
                }

                this.setHistory(history)
            }
        },
        watch: {
            sendingIsScheduled(sendingIsScheduled){
                if (sendingIsScheduled) {
                    let history = _.cloneDeep(this.history)
                    history.push({
                        method: this.currentRequest.method,
                        path: this.currentRequest.path,
                        body: this.currentRequest.body,
                        headers: this.currentRequest.headers,
                        createdAt: new Date().getTime()
                    })
                    this.setHistory(history)
                }
            }
        }
    }
</script>

<style scoped>
    .column {
        padding: 0 10px;
    }

    .clear-history {
        padding-bottom: 10px;
    }
</style>
