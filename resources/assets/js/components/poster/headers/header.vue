<template>
    <div class="header columns is-mobile">

        <div class="column">
            <input class="input"
                   type="text"
                   placeholder="Key"
                   title="Key"
                   v-model="header.key"
            >
        </div>

        <div class="column">
            <input class="input"
                   type="text"
                   placeholder="Value"
                   title="Value"
                   v-model="header.value"
            >
        </div>

        <div class="column is-narrow">
            <button type="button"
                    :disabled=""
                    class="button is-danger  is-outlined is-icon"
                    @click="remove"
            >
                <span class="icon" v-text="'×'"></span>
            </button>
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
        },
    }
</script>

<style scoped>
    .header {
        margin: 0;
    }
</style>