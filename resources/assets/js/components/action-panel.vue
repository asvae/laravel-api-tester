<template>
    <form class="action-panel control has-addons"
          @submit.prevent="setRequestScheduled"
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
                type="button"
                @click="setRequestScheduled"
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
    import vmRequestTypeSelect from './poster/request-type-select.vue'
    import requestEditorData from './poster/request-editor/request-editor-data.js'

    import {saveRequest, updateRequest} from '../vuex/actions.js'

    export default {
        data: () => requestEditorData,
        components: {
            vmRequestTypeSelect,
        },
        vuex: {
            getters: {
                sending: (store) => store.requestEditor.sendingRequest,
                saving: (store) => store.requestEditor.savingRequest,
            },
            actions: {
                saveRequest,
                updateRequest,
                setRequestScheduled: ({dispatch}) => dispatch('SET_REQUEST_SCHEDULED')
            },
        },
        methods: {
            save (){
                // Saves or updates depending on whether request has id
                let request = this.request
                request.id ? this.updateRequest(request) : this.saveRequest(request)
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