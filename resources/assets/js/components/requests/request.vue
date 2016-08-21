<template>
    <div class="request card-item"
         :class="{selected: currentRequest === request}"
    >
        <vm-method-button
                class="is-white"
                @click="setCurrentRequest(request), scheduleRequest(request)"
                v-text="request.method"
        ></vm-method-button>

        <a @click="setCurrentRequest(request)"
           class="is-bold"
           v-text="displayedName"
        ></a>

        <a v-text="'X'"
           @click="deleteRequest(request)"
        ></a>
    </div>
</template>

<script>
    import {
            setCurrentRequest,
            scheduleRequest,
    } from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'

    export default {
        components: {
            vmMethodButton
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
                scheduleRequest,
                deleteRequest ({dispatch}, request) {
                    this.$api_demo2.ajax('requests/delete', request)
                        .then(() => {
                            dispatch('DELETE_REQUEST', request)
                        })
                },
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