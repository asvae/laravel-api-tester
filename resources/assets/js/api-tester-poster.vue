<template>
    <div class="api-tester-poster">

        <form @submit.prevent="submit" class="box">
            <div class="columns">
                <div class="column is-half">
                    <input class="input"
                           type="text"
                           placeholder="Method"
                           title="Method"
                           v-model="requestData.method"
                    >
                </div>
                <div class="column is-half">
                    <input class="input"
                           type="text"
                           placeholder="Path"
                           title="Path"
                           v-model="requestData.path"
                    >
                </div>
            </div>

            <!-- For some reason the form ignores top down submit -->
            <input type="submit" class="is-hidden"/>

            <div class="json-editor" style="height: 400px"></div>

            <div class="is-pulled-right">
                <button class="button is-primary" type="submit"
                >Send</button>
                <button class="button" type="button"
                        @click="$options.editor.set('')"
                >Clear</button>
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
    import ajaxHelper from './ajax-helper'
    import $ from 'jquery'

    export default {
        editor: null,
        data (){
            return {
                request: '',
                jsonRequest: {},
                response: {
                    isJson: false,
                    data: '',
                },
                showError: false,
            }
        },
        props: {
            requestData: {
                type: Object,
                default (){
                    return {method: 'GET', path: '/',}
                }
            }
        },
        ready() {
            let container = $(this.$el).find('.json-editor')[0]
            this.$options.editor = new JSONEditor(container, {mode: 'code'})
        },
        watch: {
            request(request){
                this.parseRequest(request)
            }
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
            submit (){
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

                ajaxHelper(this.requestData.method, '/'+this.requestData.path, request, this)
                        .always(function (data, status, xhr) {

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
