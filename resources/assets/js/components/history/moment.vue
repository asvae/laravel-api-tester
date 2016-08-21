<template>
    <div>
        <vm-card-item>
            <div
                    class="moment-box"
                    @click="setCurrentRequest(moment)"
            >
                <div class="time" v-text="time"></div>
                <a
                        v-text="moment.path"
                        style="white-space: nowrap"
                ></a>
            </div>
        </vm-card-item>
    </div>
</template>

<script>
    import {setCurrentRequest, scheduleRequest} from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'
    import vmCardItem from '../ligth-components/card-item.vue'
    import _ from 'lodash'
    import moment from 'moment'

    export default {
        components: {vmMethodButton, vmCardItem},
        vuex: {
            getters: {
                currentRoute: (store) => store.routes.currentRoute,
            },
            actions: {setCurrentRequest, scheduleRequest}
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
    .moment-box{
        padding: 5px;
    }
</style>