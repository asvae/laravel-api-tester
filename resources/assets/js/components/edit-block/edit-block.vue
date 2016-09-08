<template>
    <div class="edit-block">
        <vm-request-editor></vm-request-editor>
        <vm-response-viewer></vm-response-viewer>
    </div>
</template>

<script>
    import _ from 'lodash'

    import vmRequestEditor from './request-editor/request-editor.vue'
    import vmResponseViewer from './response-viewer/response-viewer.vue'

    import requestEditorData from './request-editor/request-editor-data.js'

    export default {
        components: {
            vmRequestEditor,
            vmResponseViewer,
        },
        data: () => requestEditorData, // {request: {}}
        watch: {
            currentRequest (currentRequest){
                this.request = _.cloneDeep(currentRequest)
                this.$store.dispatch('loadCurrentRequestInfo')
            },
            // We work with scheduled requests as with stack.
            scheduledRequests (requests){
                if (requests.length === 0) {
                    return
                }

                let request = requests[0]
                this.$store.dispatch('sendRequest', request)

                // TODO promise?
                this.$store.dispatch('addMoment', request)
                this.$store.dispatch('shiftRequest')
            },
        },
        computed: {
            currentRequest (){
                return this.$store.getters.currentRequest
            },
            scheduledRequests (){
                return this.$store.getters.scheduledRequests
            }
        }
    }
</script>

<style scoped>

</style>
