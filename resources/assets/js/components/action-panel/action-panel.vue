<template>
    <form class="action-panel control has-addons"
          @submit.prevent="scheduleSending(true)"
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
            <i class="fa fa-send-o"></i>
        </button>

        <button class="button"
                :class="{'is-loading': saving}"
                type="button"
                @click="save"
                title="Save"
        >
            <i class="fa fa-save"></i>
        </button>

        <button class="button is-icon"
                type="button"
                @click="saveRequest(request)"
                title="Copy"
        >
            <i class="fa fa-files-o"></i>
        </button>

    </form>
</template>

<script>
    import vmRequestTypeSelect from './request-type-select.vue'
    import requestEditorData from '../poster/request-editor/request-editor-data.js'

    import {
            saveRequest,
            updateRequest,
            loadRequests,
            setCurrentRequest,
            scheduleSending
    } from '../../vuex/actions.js'

    export default {
        data: () => requestEditorData,
        components: {
            vmRequestTypeSelect,
        },
        vuex: {
            getters: {
                sending: (store) => store.requestEditor.isSending,
                saving: (store) => store.requestEditor.isSaving,
            },
            actions: {
                saveRequest,
                updateRequest,
                loadRequests,
                setCurrentRequest,
                scheduleSending,
            },
        },
        methods: {
            save (){
                // Saves or updates depending on whether request has id
                let request = this.request
                let afterUpdate = () => {
                    this.loadRequests()
                }

                request.id ? this.updateRequest(request, afterUpdate) : this.saveRequest(request, afterUpdate)

            },
        }
    }
</script>

<style scoped>
    .action-panel {
        margin: 0;
        width: 100%;
    }
</style>