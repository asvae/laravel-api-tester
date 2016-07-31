<template>
    <div class="headers">
        <div class="tile is-ancestor">
            <div class="tile is-vertical">
                <div v-for="(key, name) in headers"
                     transition="slip"
                >
                    {{ key }} {{ name }}
                </div>
            </div>
        </div>
        <vm-new-header
                @created="created"
        ></vm-new-header>
    </div>
</template>

<script>
    import vmNewHeader from './new-header.vue'
    import _ from 'lodash'

    export default {
        components: {
            vmNewHeader,
        },
        data (){
            return {}
        },
        methods: {
            created (key, value){
                this.headers[key] = value
                this.headers = _.clone(this.headers)
                this.$emit('update', this.headers)
            }
        },
        props: {
            headers: {
                type: Object,
                default(){
                    return {}
                },
                required: true
            }
        }
    }
</script>

<style>

</style>