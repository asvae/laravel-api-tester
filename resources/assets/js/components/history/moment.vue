<template>
    <div class="moment">
        <div class="columns is-mobile">
            <div class="column is-narrow time" v-text="time"></div>
            <a @click="setCurrentRequest(moment)"
               class="column is-bold"
               v-text="moment.path"
               style="white-space: nowrap"
            ></a>
        </div>
    </div>
</template>

<script>
    import {setCurrentRequest, scheduleSending} from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'
    import _ from 'lodash'
    import moment from 'moment'

    export default {
        components: {
            vmMethodButton,
        },
        vuex: {
            getters: {
                currentRoute: (store) => store.currentRoute,
            },
            actions: {
                setCurrentRequest,
                scheduleSending,
            }
        },

        data(){
          return {
              time : ''
          }
        },
        props: {
            moment: {
                type: Object,
            }
        },

        ready(){
            this.start()
        },

        methods: {
            tick(){
                this.time = moment.unix(this.moment.createdAt/1000).fromNow();
            },

            start(){
                this.tick()
                setInterval(this.tick.bind(this), 1000*45)
            }
        }
    }
</script>

<style scoped>
    .time{
        color: #ccc;
    }
</style>