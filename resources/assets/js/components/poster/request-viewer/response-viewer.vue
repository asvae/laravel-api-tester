<template>
    <div class="response-viewer">
        <div class=" columns is-multiline" v-if="response">
            <div class="column is-full">
                <vm-response-viewer-navigation></vm-response-viewer-navigation>
            </div>
            <div class="column is-full">
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
    </div>
</template>

<script>
    import vmResponseViewerNavigation from './response-viewer-navigation.vue'

    import vmJsonViewer from '../../json-editor/json-viewer.vue'

    export default {
        components: {
            vmResponseViewerNavigation,
            vmJsonViewer,
        },
        computed: {
            iframeVisible (){
                if (this.mode === 'preview' && !response.isJson) {
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
        }
    }
</script>

<style scoped>

</style>