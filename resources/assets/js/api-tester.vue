Application main page.

<template>
    <div class="api-tester-main">
        <div class="top-fixed">
            <div class="columns">
                <div class="column is-narrow">
                    <vm-search-panel></vm-search-panel>
                </div>
                <div class="column">
                    <vm-action-panel :request-poster="$refs.requestPoster"
                                     v-if="$refs.requestPoster"
                    ></vm-action-panel>
                </div>
            </div>
        </div>
        <div class="bottom">
            <div class="left-side-background"></div>
            <div class="left-side">
                <div class="columns is-multiline">
                    <div class="column is-boxed">
                        <vm-navigation-tabs :pages="['routes', 'requests']"
                                            :mode.sync="mode"
                        ></vm-navigation-tabs>
                    </div>
                    <div class="column is-full">
                        <vm-routes-selector v-show="mode === 'routes'"
                        ></vm-routes-selector>
                        <vm-requests-selector v-show="mode === 'requests'"
                        ></vm-requests-selector>
                    </div>
                    <div class="column is-full">
                    </div>
                </div>
            </div>
            <div class="right-side">
                <vm-request-poster v-ref:request-poster></vm-request-poster>
            </div>
        </div>
    </div>
</template>

<script>
    import vmSearchPanel from './components/search-panel.vue'
    import vmActionPanel from './components/action-panel/action-panel.vue'
    import vmRoutesSelector from './components/routes/routes-selector.vue'
    import vmRequestsSelector from './components/requests/requests-selector.vue'
    import vmRequestPoster from './components/poster/request-poster.vue'

    import vmNavigationTabs from './components/ligth-components/navigation-tabs.vue'

    export default {
        data (){
            return {
                mode: "routes",
            }
        },
        components: {
            vmActionPanel,
            vmSearchPanel,
            vmRoutesSelector,
            vmRequestsSelector,
            vmRequestPoster,

            vmNavigationTabs,
        },
    }
</script>

We decided to forsake all the responsibility stuff. It requires bulma @media
which requires scss in .vue file, which is not supported by phpstorm ATM.
<style scoped>
    .top-fixed {
        box-shadow: 0 2px 3px rgba(17, 17, 17, 0.1), 0 0 0 1px rgba(17, 17, 17, 0.1);
        width: 100%;
        padding: 10px;
        top: 0;
        left: 0;
        position: fixed;
        background-color: rgba(255, 255, 255, 0.9);
        z-index: 5;
    }

    /* Purely decorational element */
    .left-side-background {
        position: fixed;
        width: 400px;
        height: 100%;
        background-color: white;
        z-index: -1;
    }

    .left-side {
        margin-top: 52px;
        left: 0;
        position: absolute;
        width: 400px;
        min-height: calc(100% - 52px);
        padding: 10px;
    }

    .right-side {
        margin-top: 52px;
        left: calc(400px);
        position: absolute;
        padding: 10px;
        width: calc(100% - 400px);
    }
</style>

