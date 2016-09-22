<template>
    <div class="route columns is-gapless is-mobile"
         :class="{selected: $store.getters.currentRoute === route}"
    >
        <vm-method-button
                class="is-white column is-narrow"
                @click="setAndSend"
                v-text="route.methods[0]"
        ></vm-method-button>
        <a @click="set"
           class="is-bold column"
           :class="{'has-error': hasError}"
           v-text="route.path"
           style="white-space: nowrap"
        ></a>
    </div>
</template>

<script>
    import vmMethodButton from '../ligth-components/method-button.vue'
    import _ from 'lodash'

    export default {
        components: {
            vmMethodButton
        },
        computed: {
            hasError (){
                return this.route.errors.length !== 0
            }
        },
        props: ['route'],
        methods: {
            convertToRequest (){
                return {
                    method: this.route.methods[0],
                    path: this.route.path,
                    name: "",
                    body: this.route.hasOwnProperty('body') ? this.route.body : '""',
                    wheres: this.route.hasOwnProperty('wheres') ? this.route.wheres : {},
                    headers: this.route.hasOwnProperty('headers') ? this.route.headers : [],
                    config: {
                        addCRSF: true,
                    }
                }
            },
            setAndSend(){
                this.set()
                this.$store.dispatch('scheduleRequest', this.convertToRequest())
            },
            set(){
                this.$store.dispatch('setCurrentRequest', this.convertToRequest())
                this.$store.dispatch('setResponse', null)

                this.$store.dispatch('setInfo', this.route)
            },
        }
    }
</script>

<style scoped>
    .route.selected {
        border-right: 2px solid rgb(255, 82, 82);
        background-color: #e2fcff;
    }
    .route.columns {
        margin: 0;
        border-right: 2px solid transparent;
        border-top: 1px solid rgba(0, 0, 0, .025);
    }
    .route .has-error {
        color: #FF5252;
    }
</style>