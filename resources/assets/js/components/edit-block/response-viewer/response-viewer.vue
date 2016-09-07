<template>
    <div class="response-viewer">
        <div class="card is-fullwidth" v-if="response">
            <div class="card-content">
                <vm-navigation-tabs
                        class="is-boxed"
                        :pages="['data', 'headers', 'preview', 'redirects']"
                        :mode="mode"
                        @changed="$store.dispatch('setResponseViewerMode', $arguments[0])"
                ></vm-navigation-tabs>
                <iframe :class="{'is-visible': iframeVisible}"
                        :srcdoc="response.data"
                ></iframe>
                <vm-json-viewer
                        v-show="mode === 'preview' && response.isJson"
                        :json="response.data"
                ></vm-json-viewer>
                <div v-show="mode === 'data'">
                    <pre style="overflow-x: auto;"
                         v-if="! response.isJson"><code>{{ response.data }}</code></pre>
                    <pre style="overflow-x: auto;" v-if="response.isJson">{{ response.data | json}}</pre>
                </div>
                <div v-show="mode === 'headers'" class="content">
                    <pre><code>Status: {{response.status}} {{response.statusText}}</code></pre>
                    <pre><code>{{response.headers}}</code></pre>
                </div>
                <div v-show="mode === 'redirects'" class="content">
                    <table class="table"
                           v-if="response.redirects !== undefined && response.redirects.length !== 0">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Location</th>
                        </tr>
                        <tr v-for="redirect in response.redirects">
                            <td>{{$index+1}}.</td>
                            <td v-text="redirect.status"></td>
                            <td v-text="redirect.location"></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="notification"
                         v-if="response.redirects !== undefined && response.redirects.length === 0"
                    >
                        No redirects
                    </div>
                </div>
            </div>
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
            response (){
                return this.$store.getters.response
            },
            mode (){
                return this.$store.getters.responseViewerMode
            },
            iframeVisible (){
                return this.mode === 'preview' && !this.response.isJson;
            }
        },
    }
</script>

<style scoped>
    iframe {
        width: 100%;
        height: 0;
    }

    table.table {
        background-color: transparent;
    }

    table.table tbody tr th, table.table tbody tr td {
        color: #69707a !important;
    }

    iframe.is-visible {
        height: 700px;
    }
</style>