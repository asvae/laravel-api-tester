<template>
    <div class="request-poster">

        <div v-if="request">

            <div class="columns is-multiline">
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
                        v-if="request_mode === 'info'"
                ></vm-route-details>

                <div class="column is-full" v-if="request_mode === 'headers'">
                    <vm-headers :headers="request.headers"
                                @updated="request.headers = $arguments[0]"
                    ></vm-headers>
                </div>
                <div class="column is-full">
                    <vm-response-navigation></vm-response-navigation>
                </div>
                <div class="column is-full">
                    <!--
                        Здесь пришлось приментить хак.
                        Дело в том, что display:none сбрасывет весь прогресс айфрейма.
                        И при повторном отображении, весь его код исполняется и рассчитывается заново.
                        visible:hidden тоже не подходит, так как скрытый элемент
                        продолжает занимать пространство на странице.
                        Поэтому, я не придумал ничего лучше, как скрыть элемент установил height:0
                     -->
                    <iframe class="{{ iFrameIsVisible(response) ? 'is_vis' : ''}}"
                            :srcdoc="response.data"
                    ></iframe>
                    <vm-json-viewer v-show="response_mode === 'preview' && response.isJson"
                                    :json="response.data"
                    ></vm-json-viewer>
                    <div v-show="response_mode === 'data'">
                        <pre v-if="! response.isJson">{{ response.data }}</pre>
                        <pre v-if="response.isJson">{{ response.data | json}}</pre>
                    </div>
                    <pre v-show="response_mode === 'headers'">{{response.headers}}</pre>
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
    import vmResponseNavigation from './response-navigation.vue'
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
            vmResponseNavigation,
            vmRouteDetails,
            vmRequestTypeSelect,
        },
        vuex: {
            getters: {
                request_mode: state => state.requestEditor.mode,
                response_mode: state => state.responseViewer.mode,
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
                    headers: ''
                },
                showError: false,
            }
        },
        methods: {
            iFrameIsVisible(response){
              if(this.response_mode === 'preview' && ! response.isJson){
                  return true
              }
              return false
            },
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
                    .always(function (data, status, jqXHR) {
                        this.isSending = false

                        let headers = false

                        if (data.responseText !== undefined) {
                            headers = data.getAllResponseHeaders()
                            data = data.responseText
                        }

                        this.response.isJson = typeof data !== 'string'
                        this.response.data = data
                        //console.log(jqXHR)
                        this.response.headers = headers ? headers : jqXHR.getAllResponseHeaders()
                    })
            }
        },
    }
</script>

<style scoped>
    iframe {
        height: 0;
        width: 100%;
    }

    iframe.is_vis {
        height: 700px;
    }
</style>
