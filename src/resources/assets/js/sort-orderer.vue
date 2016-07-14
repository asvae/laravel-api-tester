<template>
    <span v-text="value ? '/\\' : '\\/'"></span>
</template>

<script>
    export default {
        props: {
            value: {
                type: Boolean,
                default: false,
            }
        },
        watch: {
            value (value){
                this.$emit('change', value)
            }
        }
    }
</script>