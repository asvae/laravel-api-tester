<template>
    <div class="json-editor"></div>
</template>

<script>
    import abstractJsonEditor from './abstract-json-editor.vue'

    export default {
        extends: abstractJsonEditor,
        data () {
            return {
                // We have to keep state in order to figure out,
                // whether json change derived from parent or self.
                editedJson: {},
            }
        },
        watch: {
            json (json){
                // External change
                if (this.editedJson !== json){
                    this.$options.editor.set(json)
                    this.editedJson = json
                }
            }
        },
        ready() {
            this.editedJson = this.json

            let options = {
                mode: 'code',
                onChange: () => {
                    try {
                        this.editedJson = this.$options.editor.get()
                        this.$emit('changed', this.editedJson)
                    } catch (e){}
                },
            }

            this.initEditor(this.$el, options, this.editedJson)
        },
    }
</script>

<style scoped>

</style>
