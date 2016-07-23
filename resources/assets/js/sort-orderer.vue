<template>
    <span v-text="value ?  '&#9660;': '&#9650;'"></span>
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
