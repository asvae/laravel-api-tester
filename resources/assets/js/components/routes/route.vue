<template>
    <div class="route"
         :class="{selected: currentRoute === route}"
    >
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <vm-method-button
                        @click="setCurrentRequestFromRoute(route), scheduleRequest(true)"
                        v-text="route.methods[0]"
                ></vm-method-button>
            </div>
            <a @click="setCurrentRequestFromRoute(route)"
               class="column is-bold"
               v-text="route.path"
               style="white-space: nowrap"
            ></a>
        </div>
    </div>
</template>

<script>
    import {
            setCurrentRequestFromRoute,
            scheduleRequest
    } from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'

    export default {
        components: {
            vmMethodButton,
        },
        vuex: {
            getters: {
                currentRoute: (store) => store.currentRoute,
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
    }
</script>

<style scoped>
    .route.selected {
        border-right: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }

    .route {
        border-right: 2px solid transparent;
    }
</style>