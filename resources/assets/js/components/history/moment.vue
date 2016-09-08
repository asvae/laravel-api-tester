<template>
    <div class="moment"
         @click="set"
    >
        <div class="time" v-text="time"></div>
        <a v-text="moment.path"
           style="white-space: nowrap"
        ></a>
    </div>
</template>

<script>
    import vmMethodButton from '../ligth-components/method-button.vue'
    import _ from 'lodash'
    import moment from 'moment'

    export default {
        components: {
            vmMethodButton
        },
        data(){
            return {
                time: ''
            }
        },
        props: ['moment'],
        ready(){
            this.start()
        },
        methods: {
            set(){
                this.$store.dispatch('setInfo', null)
                this.$store.dispatch('setResponse', null)
                this.$store.dispatch('setCurrentRequest', this.moment)
            },
            tick(){
                this.time = moment.unix(this.moment.createdAt / 1000).fromNow();
            },
            start(){
                this.tick()
                setInterval(this.tick.bind(this), 1000 * 45)
            }
        }
    }
</script>

<style scoped>
    .time {
        color: #ccc;
    }

    .moment {
        padding: 4px 10px;
    }

    .moment:hover {
        background-color: rgba(31, 200, 219, 0.19);
    }
</style>