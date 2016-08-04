<template>
    <div class="header">

        <div class="columns">
            <div class="column is-4">
                <input class="input"
                       type="text"
                       placeholder="Key"
                       title="Key"
                       v-model="header.key"
                >
            </div>

            <div class="column is-6">
                <input class="input"
                       type="text"
                       placeholder="Value"
                       title="Value"
                       v-model="header.value"
                >
            </div>

            <div class="column is-2">
                <button type="button"
                        class="button is-danger is-icon"
                        @click="remove"
                >
                    <span class="icon" v-text="'×'"></span>
                </button>
            </div>
        </div>

    </div>
</template>

<script>
    import _ from 'lodash'

    export default {
        data (){
            return {}
        },
        methods: {
            remove (){
                this.$emit('removed', this.header)
            },
        },
        props: {
            header: {
                type: Object,
                required: true,
            },
        }
    }
</script>

<style>

</style>