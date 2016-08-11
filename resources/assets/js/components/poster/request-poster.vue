<template>
    <div class="request-poster">

        <div v-if="request">

            <div class="columns is-multiline">
                <div class="column is-full">
                    <vm-poster-navigation></vm-poster-navigation>
                </div>

                <form class="column is-full"
                      v-if="mode === 'request'"
                      @submit.prevent="save"
                >
                    <input class="input"
                           type="text"
                           placeholder="Name"
                           title="Name"
                           v-model="request.name"
                    >
                    <input type="submit" class="is-hidden">
                </form>

                <vm-route-details
                        class="column is-full"
                        v-if="mode === 'info'"
                ></vm-route-details>

                <div class="column is-full" v-if="mode === 'request'">
                    <vm-json-editor :json="request.body"
                                    style="height: 300px"
                                    @changed="request.body = $arguments[0], changed = true"
                    ></vm-json-editor>
                </div>

                <div class="column is-full" v-if="mode === 'headers'">
                    <vm-headers :headers="request.headers"
                                @updated="request.headers = $arguments[0]"
                    ></vm-headers>
                </div>

                <div class="column is-full">
                    <iframe style="width: 100%; height: 700px;"
                            v-if="! response.isJson"
                            :srcdoc="response.data"
                    ></iframe>

                    <vm-json-viewer v-if="response.isJson"
                                    :json="response.data"
                    ></vm-json-viewer>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'
    import vmJsonEditor from '../json-editor/json-editor.vue'
    import vmJsonViewer from '../json-editor/json-viewer.vue'
    import vmPosterNavigation from './poster-navigation.vue'
    import vmRequestTypeSelect from './request-type-select.vue'

    import vmHeaders from './headers.vue'
    import vmRouteDetails from './route-details.vue'

    import * as actions from '../../vuex/actions.js'

    import requestStorage from './request-storage.js'

    export default {
        components: {
            vmJsonEditor,
            vmJsonViewer,
            vmHeaders,
            vmPosterNavigation,
            vmRouteDetails,
            vmRequestTypeSelect,
        },
        vuex: {
            getters: {
                mode: state => state.requestEditor.mode,
                currentRequest: state => state.currentRequest,
                isRequestScheduled: state => state.isRequestScheduled,
                isSavingRequest: state => state.requestEditor.saving
            },
            actions,
        },
        ready (){
            this.refresh()
        },
        watch: {
            currentRequest(){
                this.refresh()
            },
            request(request){
                this.parseRequest(request)
            },
            isRequestScheduled (isScheduled){
                if (isScheduled) {
                    this.scheduleRequest(false)
                    this.send()
                }
            },
        },
        data (){
            return {
                request: null,
                changed: false,
                jsonRequest: {},
                isSending: false,
                response: {
                    isJson: false,
                    data: '',
                },
                showError: false,
            }
        },
        methods: {
            refresh (){
                this.request = _.cloneDeep(this.currentRequest)
                this.changed = false
                this.getCurrentRequestRoute()
            },
            parseRequest (request){
                try {
                    let escaped = decodeURI(request)
                    this.jsonRequest = JSON.parse(escaped)
                    this.isRequestError = false
                }
                catch (error) {
                    this.isRequestError = true
                }
            },
            copy (){
                this.saveRequest(this.request)
            },
            save (){
                // Saves or updates depending on whether request has id
                let request = this.request
                request.id ? this.updateRequest(request) : this.saveRequest(request)
            },
            send (){
                this.isSending = true

                let path = this.request.path
                // Process routes that have leading slash.
                path = path === '/' ? path : '/' + path

                this.$api.ajax(this.request.method, path, this.request.body, this.request.headers)
                    .always(function (data) {
                        this.isSending = false

                        if (data.responseText !== undefined) {
                            data = data.responseText
                        }

                        this.response.isJson = typeof data !== 'string'
                        this.response.data = data
                    })
            }
        },
    }
</script>

<style scoped>

</style>
