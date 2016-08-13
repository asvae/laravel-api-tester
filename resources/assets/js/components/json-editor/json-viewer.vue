<template>
    <div></div>
</template>

<script>
    import $ from 'jquery'
    import abstractJsonEditor from './abstract-json-editor.vue'

    export default {
        extends: abstractJsonEditor,
        watch: {
            json (json){
                this.$options.editor.set(json)
            }
        },
        ready() {
            let options = {
                mode: 'view',
            }

            this.initEditor(this.$el, options, this.json)
        },
    }
</script>

<style scoped>

</style>
