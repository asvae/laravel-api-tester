<template>
    <div class="request"
         :class="{selected: currentRequest === request}"
    >
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <vm-method-button
                        @click="setCurrentRequest(request), scheduleRequest(true)"
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
    import {setCurrentRequest, scheduleRequest, deleteRequest} from '../../vuex/actions.js'
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
                scheduleRequest,
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
    .request.selected {
        border-left: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }

    .request {
        border-left: 2px solid transparent;
    }
</style>