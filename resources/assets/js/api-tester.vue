Application main page.

<template>
    <div class="api-tester-main">
        <div class="container is-fluid">
            <div class="columns">
                <div class="column is-4">
                    <div class="columns is-multiline">
                        <div class="column is-full">
                            <vm-search-panel></vm-search-panel>
                        </div>
                        <div class="column is-full">
                            <vm-routes-selector></vm-routes-selector>
                        </div>
                        <div class="column is-full">
                            <vm-requests-selector></vm-requests-selector>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <vm-request-poster></vm-request-poster>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vmSearchPanel from './components/search-panel.vue'
    import vmRoutesSelector from './components/routes/routes-selector.vue'
    import vmRequestsSelector from './components/requests/requests-selector.vue'
    import vmRequestPoster from './components/poster/request-poster.vue'

    export default {
        components: {
            vmSearchPanel,
            vmRoutesSelector,
            vmRequestsSelector,
            vmRequestPoster,
        },
    }
</script>

<style>

</style>

