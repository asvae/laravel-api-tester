<template>
    <form class="action-panel control has-addons"
          @submit.prevent="$store.dispatch('scheduleRequest', request)"
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

        <button class="button is-success"
                :class="{'is-loading': sending}"
                type="submit"
                title="Send"
        >
            <span class="icon">
                <i class="fa fa-send-o"></i>
            </span>
            <span>Send</span>
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

    export default {
        data: () => requestEditorData,
        components: {
            vmRequestTypeSelect,
        },
        methods: {
            save (){
                // Saves or updates depending on whether request has id
                let action = this.request.id ? 'updateRequest' : 'saveRequest'

                this.$store.dispatch(action, this.request)
            },
            copy(){
                // Just saves request.
                this.$store.dispatch('saveRequest', this.request)
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

    .button {
        overflow: hidden;
    }
</style>