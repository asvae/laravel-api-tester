<template>
    <div class="request"
         :class="{selected: currentRequest === request}"
    >
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <vm-method-button
                        @click="setCurrentRequest(request), scheduleSending(true)"
                        v-text="request.method"
                ></vm-method-button>
            </div>
            <a @click="setCurrentRequest(request)"
               class="column is-bold"
               v-text="displayedName"
               style=""
            ></a>
            <a class="column is-narrow"
               v-text="'X'"
               @click="deleteRequest(request)"
            ></a>
        </div>
    </div>
</template>

<script>
    import {setCurrentRequest, scheduleSending, deleteRequest} from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'

    export default {
        components: {
            vmMethodButton,
        },
        computed: {
            displayedName (){
                return this.request.name ? this.request.name : this.request.path
            }
        },
        data (){
            return {}
        },
        vuex: {
            actions: {
                setCurrentRequest,
                scheduleSending,
                deleteRequest,
            },
        },
        props: {
            request: {
                type: Object,
            }
        }
    }
</script>

<style scoped>

</style>