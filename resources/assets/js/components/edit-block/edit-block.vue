<template>
    <div class="request-poster">
        <vm-request-editor></vm-request-editor>
        <vm-response-viewer></vm-response-viewer>
    </div>
</template>

<script>
    import _ from 'lodash'

    import vmRequestEditor from './request-editor/request-editor.vue'
    import vmResponseViewer from './response-viewer/response-viewer.vue'

    import RandExp from 'randexp'

    import {scheduleRequest} from '../../vuex/actions.js'

    import requestEditorData from './request-editor/request-editor-data.js'

    export default {
        components: {
            vmRequestEditor,
            vmResponseViewer,
        },
        data: () => requestEditorData,
        methods: {
            // Ask laravel what route are we dealing with, if any.
            getRequestInfo () {
                let request = this.request

                // If we won't stick the following header laravel will
                // process the request as usual and won't give any info.
                let headers = _.cloneDeep(request.headers)
                headers.push({key: 'X-Api-Tester', value: 'route-info'})

                // Modifies path if wheres are declared in request.
                // Otherwise, we'll send to unmodified path.
                let wheres = _.cloneDeep(request.wheres)
                for (let index in wheres) {
                    let mocker = new RandExp(new RegExp(wheres[index]))
                    let dummy = new RegExp('{' + index + '}', 'g')

                    path = path.replace(dummy, mocker.gen())
                }

                // Do sending.
                this.$api_demo2.load({
                    method: request.method,
                    path: this.request.path,
                    data: request.body,
                    headers,
                }).then((response) => {
                    console.log(response)
                    this.setRequestInfo(response.data)
                }).catch(() => {
                    this.setRequestInfo(null)
                })
            },
            send (){
                this.setResponse(null)
                this.setIsSending(true)

                let request = this.request

                let headers = _.cloneDeep(this.request.headers)
                headers.push({key: 'X-Api-Tester', value: 'catch-redirect'})

                this.getResult(request.method, request.path, request.body, headers)
            },
//            followRedirect(data, redirects, headers){
//                redirects.push(data)
//                this.getResult('GET', data.location, null, headers, redirects)
//            },
            getResult(method, path, body, headers, redirects = []){
                this.$api.ajax(method, path, body, headers)
                    .always(function (dataOrXHR, status, XHROrError) {
                        // NOTE Jquery ajax is sometimes not quite sane.
                        let xhr = dataOrXHR.hasOwnProperty('responseText') ? dataOrXHR : XHROrError
                        let data = xhr.responseText
                        try {
                            data = JSON.parse(data)
                        } catch (e) {
                        }

                        // TODO What this line is doing?
                        if (xhr.getResponseHeader('X-Api-Tester') === 'redirect') {
                            this.followRedirect(data.data, redirects, headers);
                            return
                        }

                        let response = {
                            data,
                            isJson: typeof data !== 'string',
                            headers: xhr.getAllResponseHeaders(),
                            redirects: redirects
                        }

                        this.setResponse(response)
                        this.setIsSending(false)
                    })
            }
        },
        vuex: {
            getters: {
                history: state => state.history,
                scheduledRequests: state => state.requests.scheduledList,
                sendingIsScheduled: state => state.request.scheduleRequest,
                infoMode: (state) => state.infoMode,
            },
            actions: {
                scheduleRequest,
                setInfoError: ({dispatch}, bool) => dispatch('SET_INFO_ERROR', bool),
                setResponse: ({dispatch}, response) => dispatch('SET_RESPONSE', response),
                setRequestInfo: ({dispatch}, route) => dispatch('SET_REQUEST_INFO', route),
                setCurrentRequest: ({dispatch}, route) => dispatch('SET_CURRENT_REQUEST', route),
                setIsSending: ({dispatch}, sending) => dispatch('SET_REQUEST_IS_SENDING', sending),
                setViewerMode: ({dispatch}, mode) => dispatch('SET_VIEWER_MODE', mode),
                setEditorMode: ({dispatch}, mode) => dispatch('SET_EDITOR_MODE', mode),
            },
        },
        watch: {
            currentRequest (currentRequest){

            },
            // Kinda vuex event
            scheduledRequests (requests){
                console.log(requests)
//                if (isScheduled) {
//                    this.send()
//                    this.scheduleSending(false)
//                }
            },
        },
    }
</script>

<style scoped>

</style>
