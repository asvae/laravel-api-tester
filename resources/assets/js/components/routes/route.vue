<template>
    <div class="route columns is-gapless is-mobile"
         :class="{selected: currentRoute === route}"
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
    import {
            setCurrentRequest,
            setRequestInfo,
            scheduleRequest,
    setResponse,
    } from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'
    import _ from 'lodash'

    export default {
        components: {
            vmMethodButton
        },
        computed: {
            routeRequest (){
                return {
                    method: this.route.methods[0],
                    path: this.route.path,
                    name: "",
                    body: this.route.hasOwnProperty('body') ? this.route.body : "",
                    wheres: this.route.hasOwnProperty('wheres') ? this.route.wheres : {},
                    headers: this.route.hasOwnProperty('headers') ? this.route.headers : [],
                    config: {
                        addCRSF: true,
                    }
                }
            },
            hasError (){
                return this.route.errors.length !== 0
            }
        },
        vuex: {
            getters: {
                currentRoute: (store) => store.routes.currentRoute,
            },
            actions: {
                setCurrentRequest,
                setRequestInfo,
                scheduleRequest,
                setResponse,
            }
        },
        props: {
            route: {
                type: Object,
            }
        },
        methods: {
            setAndSend(){
                this.set()
                this.scheduleRequest(this.routeRequest)
            },
            set(){
                this.setCurrentRequest(this.routeRequest)
                this.setResponse(null)
                this.setRequestInfo(this.route)
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