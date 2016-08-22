<template>
    <form class="action-panel control has-addons"
          @submit.prevent="scheduleRequest(request)"
    >
        <vm-request-type-select
                :method="request.method"
                @changed="request.method = $arguments[0]"
        ></vm-request-type-select>

        <input class="input is-expanded"
               type="text"
               placeholder="Path"
               title="Path"
               v-model="request.path"
        >

        <button class="button is-success is-icon"
                :class="{'is-loading': sending}"
                type="submit"
                title="Send"
        >
            <span class="icon">
                <i class="fa fa-send-o"> </i>
            </span>
            <span>Send request</span>
        </button>

        <button class="button is-primary"
                :class="{'is-loading': $activeActions['requests/store'] || $activeActions['requests/update']}"
                type="button"
                @click="save"
                title="Save"
        >
            <i class="fa fa-save"></i>
        </button>

        <button class="button is-icon is-primary"
                :class="{'is-disabled': ! request.id}"
                type="button"
                @click="copy"
                title="Copy"
        >
            <i class="fa fa-files-o"></i>
        </button>
    </form>
</template>

<script>
    import vmRequestTypeSelect from './request-type-select.vue'
    import requestEditorData from '../edit-block/request-editor/request-editor-data.js'

    import {
            loadRequests,
            setCurrentRequest,
            scheduleRequest
    } from '../../vuex/actions.js'

    import {saveRequest, updateRequest} from './request-actions.js'

    export default {
        data: () => requestEditorData,
        components: {
            vmRequestTypeSelect,
        },
        vuex: {
            getters: {
                sending: (store) => store.request.isSending,
                saving: (store) => store.request.isSaving,
            },
            actions: {
                saveRequest,
                updateRequest,
                loadRequests,
                setCurrentRequest,
                scheduleRequest,
            },
        },
        methods: {
            save (){
                // Saves or updates depending on whether request has id
                let afterUpdate = () => {
                    this.loadRequests()
                }

                let request = this.request
                request.id ? this.updateRequest(request, afterUpdate) : this.saveRequest(request, afterUpdate)
            },
            copy(){
                // Just saves request.
                let afterSave = () => {
                    this.loadRequests()
                }
                this.saveRequest(this.request, afterSave)
            }
        }
    }
</script>

<style scoped>
    .button, .button:hover, .button:active {
        border-color: #0092a2 !important;
    }

    .action-panel {
        margin: 0;
        width: 100%;
    }

    .input, .input:hover {
        transition: all ease .1s;
        background-color: #0092a2;
        border-color: #0092a2;
        color: #c6faff;
        font-weight: 100;
        font-family: droid sans mono, consolas, monospace, courier new, courier, sans-serif;
    }

    .input:focus {
        background-color: #ffffff;
        color: #69707a;
        border-color: #1fc8db;
    }
</style>