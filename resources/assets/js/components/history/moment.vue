<template>
    <div class="moment"
         :class="{selected: currentRoute === moment}"
    >
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <vm-method-button
                        @click="setCurrentRequest(moment), scheduleRequest(true)"
                        v-text="moment.method"
                ></vm-method-button>
            </div>
            <a @click="setCurrentRequest(moment)"
               class="column is-bold"
               v-text="moment.path"
               style="white-space: nowrap"
            ></a>
        </div>
    </div>
</template>

<script>
    import {setCurrentRequest, scheduleRequest} from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'
    import _ from 'lodash'

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
                scheduleRequest
            }
        },
        props: {
            moment: {
                type: Object,
            }
        },
        methods: {
            hasErrors(route){
                return ! _.isEmpty(route.errors);
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
        border-right: 2px solid transparent;
    }

    .route .has-error{
        color: #FF5252;
    }
</style>