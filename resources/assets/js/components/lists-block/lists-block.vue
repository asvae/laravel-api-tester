<template>
    <div class="lists-block">
        <vm-navigation-tabs
                :pages="['routes', 'requests', 'history']"
                :mode.sync="mode"
        ></vm-navigation-tabs>
        <vm-routes-selector v-show="mode === 'routes'"
        ></vm-routes-selector>
        <vm-requests-selector v-show="mode === 'requests'"
        ></vm-requests-selector>
        <vm-history-selector v-show="mode === 'history'"
        ></vm-history-selector>
    </div>
</template>

<script>
    import vmNavigationTabs from '../ligth-components/navigation-tabs.vue'
    import vmRoutesSelector from '../routes/routes-selector.vue'
    import vmRequestsSelector from '../requests/requests-selector.vue'
    import vmHistorySelector from '../history/history-selector.vue'

    export default {
        components: {
            vmNavigationTabs,
            vmRoutesSelector,
            vmRequestsSelector,
            vmHistorySelector,
        },
        data (){
            return {
                mode: "routes",
            }
        },
    }
</script>

<style scoped>

</style>