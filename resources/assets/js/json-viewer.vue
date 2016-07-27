<template>
    <div></div>
</template>

<script>
    import $ from 'jquery'
    import jsonEditor from './jsonEditorMixin'

    export default {
        extends: jsonEditor,

        watch: {
            json (json){
                this.$options.editor.set(json)
            }
        },
        ready() {
            let options = {
                mode: 'view'
            }

            this.$options.editor = new JSONEditor(this.$el, options, this.json)
        },
    }
</script>

<style>

</style>
