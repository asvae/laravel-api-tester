<template>
    <div class="api-tester-poster">

        <form @submit.prevent="submit"
              class="box"
        >
            <div class="columns" v-if="request">
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
            </div>

            <!-- For some reason the form ignores top down submit -->
            <input type="submit" class="is-hidden"/>

            <div class="json-editor" style="height: 400px"></div>

            <div class="is-pulled-right">
                <button class="button is-primary"
                        type="submit"
                        v-text="'Send'"
                ></button>
                <button class="button is-primary"
                        type="button"
                        @click="save"
                        v-text="'Save'"
                ></button>
                <button class="button"
                        type="button"
                        @click="$options.editor.set('')"
                        v-text="'Clear'"
                ></button>
            </div>

            <div class="tile is-parent">
                <div class="tile is-12">
                    <div class="error"
                         v-if="showError"
                         transition="fade-out"
                    >Your JSON is incorrect!
                    </div>
                </div>
            </div>
        </form>

        <div class="box">
            <iframe style="width: 100%; height: 700px;"
                    v-if="! response.isJson"
                    :srcdoc="response.data"
            ></iframe>

            <pre style="white-space: pre-wrap"
                 v-if="response.isJson"
                 v-text="response.data | json"
            ></pre>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'

    import * as actions from './vuex/actions.js'

    export default {
        editor: null, // Json editor instance is bound to component.
        vuex: {
            getters: {
                currentRequest: state => state.currentRequest,
                isRequestScheduled: state => state.isRequestScheduled,
            },
            actions,
        },
        watch: {
            currentRequest(request){
                this.request = _.clone(request)
            },
            request(request){
                this.parseRequest(request)
            },
            isRequestScheduled (isScheduled){
                if (isScheduled){
                    this.submit()
                }
            }
        },
        data (){
            return {
                request: null,
                jsonRequest: {},
                response: {
                    isJson: false,
                    data: '',
                },
                showError: false,
            }
        },
        ready() {
            let container = $(this.$el).find('.json-editor')[0]
            this.$options.editor = new JSONEditor(container, {mode: 'code'})

            this.request = this.currentRequest
        },
        methods: {
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
                this.$api.ajax('POST', 'requests', this.request)
                    .then(function (data) {
                        this.setCurrentRequest(data.data)
                        this.loadRequests()
                    })
            },
            submit (){
                this.scheduleRequest(false)

                let request

                try {
                    request = this.$options.editor.get()
                }
                catch (e) {
                    this.showError = true
                    setTimeout(function () {
                        this.showError = false
                    }.bind(this), 3000)
                    return
                }

                this.$api.ajax(this.request.method, '/' + this.request.path, request)
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
