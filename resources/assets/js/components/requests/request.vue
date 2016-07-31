<template>
    <div class="request"
         :class="{selected: currentRequest === request}"
    >
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <button class="button is-small is-active"
                        @click="setCurrentRequest(request), scheduleRequest(true)"
                        v-text="request.method"
                ></button>
            </div>
            <a @click="setCurrentRequest(request)"
               class="column is-bold"
               v-text="displayedName"
               style="white-space: nowrap"
            ></a>
            <a class="column is-narrow"
               v-text="'X'"
               @click="deleteRequest(request)"
            ></a>
        </div>
    </div>
</template>

<script>
    import * as actions from '../../vuex/actions.js'

    export default {
        data (){
            return {}
        },
        vuex: {
            actions,
        },
        props: {
            request: {
                type: Object,
            }
        },
        computed: {
            displayedName (){
                return this.request.name ? this.request.name : this.request.path
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