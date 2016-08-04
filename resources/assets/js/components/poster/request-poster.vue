<template>
    <div class="request-poster">

        <div v-if="request">

            <div class="columns">
                <div class="column is-full">
                    <vm-poster-navigation
                            :page="page"
                            @changed="page = $arguments[0]"
                    ></vm-poster-navigation>
                </div>
            </div>

            <form @submit.prevent="send"
                  class="columns is-multiline"
                  v-if="page === 'request'"
            >
                <div class="column is-half">
                    <input class="input"
                           type="text"
                           placeholder="Method"
                           title="Method"
                           v-model="request.method"
                    >
                </div>

                <div class="column is-half">
                    <input class="input"
                           type="text"
                           placeholder="Path"
                           title="Path"
                           v-model="request.path"
                    >
                </div>

                <div class="column is-half">
                    <input class="input"
                           type="text"
                           placeholder="Name"
                           title="Name"
                           v-model="request.name"
                    >
                </div>

                <input type="submit" class="is-hidden"/>
            </form>

            <div class="columns is-multiline">

                <div class="column is-full" v-if="page === 'request'">
                    <vm-json-editor :json="request.body"
                                    style="height: 300px"
                                    @changed="request.body = $arguments[0], changed = true"
                    ></vm-json-editor>
                </div>

                <div class="column is-full" v-if="page === 'headers'">
                    <vm-headers :headers="request.headers"
                                @updated="request.headers = $arguments[0]"
                    ></vm-headers>
                </div>

                <div class="column is-full">
                    <div class="columns">
                        <div class="column is-full error"
                             v-if="showError"
                             transition="fade-out">
                            Your JSON is incorrect!
                        </div>
                        <div class="column is-narrow">
                            <button class="button is-success"
                                    :class="{'is-loading': isSending}"
                                    type="button"
                                    @click="send"
                                    v-text="'Send'"
                            ></button>
                        </div>
                        <div class="column"></div>
                        <div class="column is-narrow">
                            <button class="button is-primary"
                                    type="button"
                                    @click="save"
                                    v-text="request.id ? 'Update' : 'Save'"
                            ></button>
                            <button class="button is-icon"
                                    type="button"
                                    @click="copy"
                                    title="Copy"
                            >
                                <span class="icon" v-text="'⎘'"></span>
                            </button>
                        </div>
                    </div>
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
    import vmJsonEditor from '../editor/json-editor.vue'
    import vmJsonViewer from '../editor/json-viewer.vue'
    import vmPosterNavigation from './poster-navigation.vue'

    import vmHeaders from './headers.vue'

    import * as actions from '../../vuex/actions.js'

    export default {
        components: {
            vmJsonEditor,
            vmJsonViewer,
            vmHeaders,
            vmPosterNavigation,
        },
        vuex: {
            getters: {
                currentRequest: state => state.currentRequest,
                isRequestScheduled: state => state.isRequestScheduled,
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
                page: 'request',
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
    .error {
        color: rgb(213, 0, 0);
        font-size: 12px;
        margin-top: 3px;
        display: block;
    }
</style>
