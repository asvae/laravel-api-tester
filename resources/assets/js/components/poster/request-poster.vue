<template>
    <div class="request-poster">
        <vm-request-editor></vm-request-editor>
        <vm-response-viewer></vm-response-viewer>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'

    import vmRequestEditor from './request-editor/request-editor.vue'
    import vmResponseViewer from './request-viewer/response-viewer.vue'

    import {getCurrentRequestRoute, scheduleRequest} from '../../vuex/actions.js'

    import requestEditorData from './request-editor/request-editor-data.js'

    export default {
        components: {
            vmRequestEditor,
            vmResponseViewer,
        },
        data: () => requestEditorData,
        methods: {
            refresh (){
                this.request = _.cloneDeep(this.currentRequest)
                this.getCurrentRequestRoute()
            },
            send (){
                let path = this.request.path
                // Process routes that have leading slash.
                path = path === '/' ? path : '/' + path

                this.$api.ajax(this.request.method, path, this.request.body, this.request.headers)
                    .always(function (dataOrXHR, status, XHROrError) {
                        // NOTE Jquery ajax is sometimes not quite sane.

                        let xhr
                        if (dataOrXHR.responseText !== undefined) {
                            xhr = dataOrXHR
                        } else {
                            xhr = XHROrError
                        }

                        let data = xhr.responseText

                        let response = {
                            data,
                            isJson: typeof data !== 'string',
                            headers: xhr.getAllResponseHeaders(),
                        }

                        this.setResponse(response)
                    })
            }
        },
        ready (){
            this.refresh()
        },
        vuex: {
            getters: {
                currentRequest: state => state.currentRequest,
                isRequestScheduled: state => state.isRequestScheduled,
            },
            actions: {
                scheduleRequest,
                getCurrentRequestRoute,
                setResponse: ({dispatch}, response) => dispatch('SET_RESPONSE', response)
            },
        },
        watch: {
            currentRequest(){
                this.refresh()
            },
            // Kinda vuex event
            isRequestScheduled (isScheduled){
                if (isScheduled) {
                    this.scheduleRequest(false)
                    this.send()
                }
            },
        },
    }
</script>

<style scoped>
    iframe {
        height: 0;
        width: 100%;
    }

    iframe.is-hidden {
        height: 700px;
    }
</style>
