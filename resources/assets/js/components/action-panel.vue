<template>
    <form class="action-panel control has-addons"
          @submit.prevent="requestPoster.send"
    >
        <vm-request-type-select
                :method="requestPoster.request.method"
                @changed="requestPoster.request.method = $arguments[0]"
        ></vm-request-type-select>
        <input class="input is-expanded"
               type="text"
               placeholder="Path"
               title="Path"
               v-model="requestPoster.request.path"
        >
        <button class="button is-success is-icon"
                :class="{'is-loading': requestPoster.isSending}"
                type="button"
                @click="requestPoster.send"
                title="Send"
        >
            <i class="fa fa-send-o"></i>
        </button>
        <button class="button"
                :class="{'is-loading': requestPoster.isSavingRequest}"
                type="button"
                @click="requestPoster.save"
                title="Save"
        >
            <i class="fa fa-save"></i>
        </button>
        <button class="button is-icon"
                type="button"
                @click="requestPoster.copy"
                title="Copy"
        >
            <i class="fa fa-files-o"></i>
        </button>
    </form>
</template>

<script>
    import vmRequestTypeSelect from './poster/request-type-select.vue'
    import Vue from 'vue'

    export default {
        components: {
            vmRequestTypeSelect,
        },
        props: {
            // This component is supposed to be tightly bound to request poster.
            // Passing by reference is intended.
            'request-poster': {
                required: true,
            }
        }
    }
</script>

<style scoped>
    .action-panel {
        margin: 0;
        width: 100%;
    }
</style>