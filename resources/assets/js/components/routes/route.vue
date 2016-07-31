<template>
    <div class="request">
        <div class="columns is-mobile">
            <div class="column is-narrow">
                <button class="button is-small is-active"
                        @click="setCurrentRequestFromRoute(route), scheduleRequest(true)"
                        v-text="route.methods[0]"
                ></button>
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
    import * as actions from '../../vuex/actions.js'

    export default {
        vuex: {
            actions,
        },
        props: {
            route: {
                type: Object,
            }
        },
    }
</script>

<style scoped>

</style>