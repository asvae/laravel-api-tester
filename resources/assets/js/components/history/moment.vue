<template>
    <div class="moment">
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <vm-method-button
                        @click="setCurrentRequest(moment), scheduleSending(true)"
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
    import {setCurrentRequest, scheduleSending} from '../../vuex/actions.js'
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
                scheduleSending,
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
</style>