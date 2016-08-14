<template>
    <div class="response-viewer">
        <div v-if="response">
            <vm-navigation-tabs class="is-boxed"
                                :pages="['data', 'headers', 'preview']"
                                :mode="mode"
                                @changed="setMode($arguments[0])"
            ></vm-navigation-tabs>
            <iframe :class="{'is-visible': iframeVisible}"
                    :srcdoc="response.data"
            ></iframe>
            <vm-json-viewer
                    v-show="response_mode === 'preview' && response.isJson"
                    :json="response.data"
            ></vm-json-viewer>
            <div v-show="mode === 'data'">
                <pre v-if="! response.isJson">{{ response.data }}</pre>
                <pre v-if="response.isJson">{{ response.data | json}}</pre>
            </div>
            <pre v-show="mode === 'headers'">{{response.headers}}</pre>
        </div>
    </div>
</template>

<script>
    import vmNavigationTabs from '../../ligth-components/navigation-tabs.vue'
    import vmJsonViewer from '../../json-editor/json-viewer.vue'

    export default {
        components: {
            vmNavigationTabs,
            vmJsonViewer,
        },
        computed: {
            iframeVisible (){
                if (this.mode === 'preview' && !this.response.isJson) {
                    return true
                }

                return false
            }
        },
        vuex: {
            getters: {
                mode: (state) => state.responseViewer.mode,
                response: (state) => state.responseViewer.response,
            },
            actions: {
                setMode: ({dispatch}, mode) => dispatch('SET_VIEWER_MODE', mode)
            },
        }
    }
</script>

<style scoped>
    iframe {
        width: 100%;
        height: 0;

    }

    iframe.is-visible {
        height: 700px;
    }
</style>