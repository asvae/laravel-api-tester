<template>

</template>

<script>
    import JSONEditor from 'jsoneditor'

    export default {
        editor: null, // Json editor instance is bound to component.
        props: {
            json: {}
        },
        methods: {
            initEditor(el, options, json){
                this.$options.editor = new JSONEditor(el, options, json)
            }
        }
    }
</script>

<style scoped>

</style>


