<template>
    <!-- Wrapper: for escape fragmentation -->
    <div>
        <vm-card-item
             :class="{selected: currentRequest === request}"
        >
            <vm-method-button
                    class="is-white"
                    @click="setCurrentRequest(request), scheduleSending(true)"
                    v-text="request.method"
            ></vm-method-button>

            <a  @click="setCurrentRequest(request)"
                class="is-bold"
                v-text="displayedName"
            ></a>
            <a  v-text="'X'"
                @click="deleteRequest(request)"
            ></a>
        </vm-card-item>
    </div>
</template>

<script>
    import {setCurrentRequest, scheduleRequest, deleteRequest} from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'
    import vmCardItem from '../ligth-components/card-item.vue'

    export default {
        components: {
            vmMethodButton, vmCardItem
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

</style>