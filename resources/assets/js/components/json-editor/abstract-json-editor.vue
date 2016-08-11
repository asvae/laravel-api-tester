<template>

</template>

<script>
    import JSONEditor from 'jsoneditor'

    export default {
        editor: null, // Json editor instance is bound to component.
        props: ['json'],
    }
</script>

<style scoped>

    /* jsoneditor color fixes */
    .api-tester-main .jsoneditor-menu {
        background-color: rgb(0, 188, 212);
        border: none;
    }

    .api-tester-main .jsoneditor {
        border: none;
    }
</style>


