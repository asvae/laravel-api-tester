<template>
    <div class="route card-item"
         :class="{selected: currentRoute === route}"
    >
        <vm-method-button
                class="is-white"
                @click="setCurrentRequestFromRoute(route), scheduleRequest(true)"
                v-text="route.methods[0]"
        ></vm-method-button>
        <a @click="setCurrentRequestFromRoute(route)"
           class="column is-bold"
           :class="{'has-error': hasErrors(route)}"
           v-text="route.path"
           style="white-space: nowrap"
        ></a>
    </div>
</template>

<script>
    import {
            setCurrentRequestFromRoute,
            scheduleRequest
    } from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'
    import _ from 'lodash'

    export default {
        components: {
            vmMethodButton
        },
        vuex: {
            getters: {
                currentRoute: (store) => store.routes.currentRoute,
            },
            actions: {
                setCurrentRequestFromRoute,
                scheduleRequest
            }
        },
        props: {
            route: {
                type: Object,
            }
        },
        methods: {
            hasErrors(route){
                return !_.isEmpty(route.errors);
            }
        }
    }
</script>

<style scoped>
    .route.selected {
        border-right: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }
    .route {
        margin: 4px 0;
        border-right: 2px solid transparent;
        border-bottom: 1px solid rgba(0, 0, 0, .025);
    }
    .route .has-error {
        color: #FF5252;
    }
</style>