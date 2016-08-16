<template>
    <div class="history-selector">
        <div class="columns is-multiline is-centered">
            <div class="column is-narrow">
                <a class="button is-bordered"
                   @click="clearHistory"
                >Clear history</a>
            </div>
            <vm-moment v-for="moment in filteredMoments"
                       class="column is-full"
                       transition="slip"
                       :moment="moment"
            ></vm-moment>
            <div class="column is-full" v-if="history.length === 0">
                <div class="content">
                    <blockquote>No history stored</blockquote>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'

    import vmMoment from './moment.vue'

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
                history: (store) => store.history,
                search: (store) => store.search,
                sendingIsScheduled: state => state.requestEditor.sendingIsScheduled,
                currentRequest: state => state.currentRequest,

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
            vmMoment,
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
                    })

                    this.setHistory(history)
                }
            }
        }
    }
</script>

<style scoped>
    .column + .column {
        padding: 0 10px;
    }
</style>
