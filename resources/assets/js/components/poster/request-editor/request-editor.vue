<template>
    <div class="request-editor">
        <div class="columns is-multiline" v-if="request">
            <div class="column is-full">
                <input class="input"
                       type="text"
                       placeholder="Name"
                       title="Name"
                       v-model="request.name"
                >
            </div>
            <div class="column is-full">
                <vm-poster-navigation></vm-poster-navigation>
            </div>
            <div class="column is-full" v-if="request_mode === 'data'">
                <vm-json-editor :json="request.body"
                                style="height: 300px"
                                @changed="request.body = $arguments[0], changed = true"
                ></vm-json-editor>
            </div>
            <vm-route-details
                    class="column is-full"
                    v-if="mode === 'info'"
            ></vm-route-details>

            <div class="column is-full" v-if="request_mode === 'headers'">
                <vm-headers :headers="request.headers"
                            @updated="request.headers = $arguments[0]"
                ></vm-headers>
            </div>
        </div>
    </div>
</template>

<script>
    import vmJsonEditor from '../../json-editor/json-editor.vue'
    import vmJsonViewer from '../../json-editor/json-viewer.vue'
    import vmRouteDetails from '../route-details.vue'
    import vmPosterNavigation from './request-editor-navigation.vue'

    import requestEditorData from './request-editor-data.js'


    export default {
        data: () => requestEditorData,
        components: {
            vmJsonEditor,
            vmJsonViewer,
            vmRouteDetails,
            vmPosterNavigation,
        },
        vuex: {
            getters: {
                mode: state => state.requestEditor.mode,
            },
        },
    }
</script>

<style scoped>

</style>