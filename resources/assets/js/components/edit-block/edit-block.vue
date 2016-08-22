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
            prepareRequest(request){
                request = _.cloneDeep(request)
                // Prepare request for jquery
                // TODO rename body to data to be more consistent.

                // Prepend '/' to path if not already prepended.
                // We need all our routes to be absolute.
                // TODO Think of better ways.
                let url = request.path[0] === '/' ? request.path : '/' + request.path

                return {
                    method: request.method,
                    url,
                    data: request.body,
                    headers: request.headers,
                }
                return request
            },
            // Ask laravel what route are we dealing with, if any.
            getRequestInfo () {
                let request = this.prepareRequest(this.request)

                // If we won't stick the following header laravel will
                // process the request as usual and won't give any info.
                request.headers.push({key: 'X-Api-Tester', value: 'route-info'})

                // Modifies path if wheres are declared in request.
                // Otherwise, we'll send to unmodified path.
                let wheres = _.cloneDeep(request.wheres)
                for (let index in wheres) {
                    let mocker = new RandExp(new RegExp(wheres[index]))
                    let dummy = new RegExp('{' + index + '}', 'g')

                    request.path = request.url.replace(dummy, mocker.gen())
                }

                // Do sending.
                console.log(request)
                this.$api.ajax(request.method, request.url, request.data, request.headers).then((response) => {
                    this.setRequestInfo(response.data)
                }).catch(() => {
                    this.setRequestInfo(null)
                })
            },
            send (request){
                request = this.prepareRequest(request)

                // Clear response viewer
                this.setResponse(null)
                // Spin loading icon
                this.setIsSending(true)

                // Apply special header so that middleware will prevent redirects.
                request.headers.push({
                    key: 'X-Api-Tester',
                    value: 'catch-redirect'
                })

                // When redirects occurs it triggers the same function recursively.
                this.getResult(request, [])
            },
            getResult(request, redirects){
                this.$api.ajax(request.method, request.url, request.data, request.headers)
                    .always(function (dataOrXHR, status, XHROrError) {
                        // NOTE Jquery ajax is sometimes not quite sane.
                        let xhr = dataOrXHR.hasOwnProperty('responseText') ? dataOrXHR : XHROrError
                        let data = xhr.responseText
                        try {
                            data = JSON.parse(data)
                        } catch (e) {
                        }

                        // Server-side might hint us that the response
                        // triggers redirect. If that's the case,
                        // we'll record redirect and move to next location.
                        if (xhr.getResponseHeader('X-Api-Tester') === 'redirect') {
                            redirects.push(data)
                            let nextRequest = {
                                method: 'GET',
                                path: data.data.location,
                                data: null,
                                headers: request.headers,
                            }
                            this.getResult(nextRequest, redirects)
                            // Early return here, request is still incomplete.
                            return
                        }

                        let response = {
                            data,
                            redirects,
                            isJson: typeof data !== 'string',
                            headers: xhr.getAllResponseHeaders(),
                        }
                        this.setResponse(response)
                        this.setIsSending(false)
                    })
            }
        },
        vuex: {
            getters: {
                scheduledRequests: state => state.request.scheduledList,
                currentRequest: state => state.requests.currentRequest
            },
            actions: {
                scheduleRequest,
                setResponse: ({dispatch}, response) => dispatch('SET_RESPONSE', response),
                setRequestInfo: ({dispatch}, route) => dispatch('SET_REQUEST_INFO', route),
                setCurrentRequest: ({dispatch}, route) => dispatch('SET_CURRENT_REQUEST', route),
                setIsSending: ({dispatch}, sending) => dispatch('SET_REQUEST_IS_SENDING', sending),
                setViewerMode: ({dispatch}, mode) => dispatch('SET_VIEWER_MODE', mode),
                setEditorMode: ({dispatch}, mode) => dispatch('SET_EDITOR_MODE', mode),
                setHistoryMoment: ({dispatch}, moment) => dispatch('SET_HISTORY', _.cloneDeep(moment)),
                shiftRequest: ({dispatch}) => dispatch('SHIFT_REQUEST'),
            },
        },
        watch: {
            currentRequest (currentRequest){
                this.request = _.cloneDeep(currentRequest)
                this.getRequestInfo()
            },
            // We work with scheduled requests as with stack.
            scheduledRequests (requests){
                if (requests.length === 0){
                    return
                }

                let request = requests[0]
                this.send(request)
                this.setHistoryMoment(request)
                this.shiftRequest()
            },
        },
    }
</script>

<style scoped>

</style>
