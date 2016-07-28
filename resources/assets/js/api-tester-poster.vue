<template>
    <div class="api-tester-poster">

        <form @submit.prevent="send"
              class="box"
              v-if="request"
        >
            <div class="columns is-multiline">
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
                <div class="column is-half">
                    <pre v-text="request.id | json"></pre>
                </div>

                <input type="submit" class="is-hidden"/>

                <div class="column is-full">
                    <vm-json-editor :json="request.body"
                                    style="height: 300px"
                                    @changed="request.body = $arguments[0], changed = true"
                    ></vm-json-editor>
                </div>
            </div>

            <!-- For some reason the form ignores top down submit -->

            <div class="columns">
                <div class="column">
                    <div class="error"
                         v-if="showError"
                         transition="fade-out"
                    >Your JSON is incorrect!
                    </div>
                </div>
                <div class="column is-narrow">
                    <button class="button is-primary"
                            type="submit"
                            v-text="'Send'"
                    ></button>
                    <button class="button is-primary"
                            type="button"
                            v-if="request.id"
                            @click="update"
                            v-text="'Update'"
                    ></button>
                    <button class="button is-primary"
                            type="button"
                            @click="save"
                            v-if="! request.id"
                            v-text="'Save'"
                    ></button>
                    <button class="button"
                            type="button"
                            @click="$options.editor.set('')"
                            v-text="'Clear'"
                    ></button>
                </div>
            </div>
        </form>

        <div class="box">
            <iframe style="width: 100%; height: 700px;"
                    v-if="! response.isJson"
                    :srcdoc="response.data"
            ></iframe>

            <vm-json-viewer response.isJson
                            :json="response.data"
            ></vm-json-viewer>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'
    import vmJsonEditor from './json-editor.vue'
    import vmJsonViewer from './json-viewer.vue'

    import * as actions from './vuex/actions.js'

    export default {
        components: {
            vmJsonEditor,
            vmJsonViewer,
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
                    this.send()
                }
            }
        },
        data (){
            return {
                request: null,
                changed: false,
                jsonRequest: {},
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
            save (){
                console.log(this.request)

                this.$api.ajax('POST', 'requests', this.request)
                    .then(function (data) {
                        this.setCurrentRequest(data.data)
                        this.loadRequests()
                    })
            },
            update (){
                console.log(this.request)

                this.$api.ajax('POST', 'requests' + this.request.id, this.request)
                    .then(function (data) {
                        this.setCurrentRequest(data.data)
                        this.loadRequests()
                    })
            },
            send (){
                this.scheduleRequest(false)

                let path = this.request.path
                // Process routes that have leading slash.
                path = path === '/' ? path : '/' + path

                this.$api.ajax(this.request.method, path, this.request.body)
                    .always(function (data) {
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

    .buttons {
        margin: 10px 0px;
        float: right;
    }
</style>
