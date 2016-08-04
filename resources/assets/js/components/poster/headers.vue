<template>
    <div class="headers">
        <vm-header
                v-for="header in headers"
                :header="header"
                @removed="headers.$remove(header)"
        ></vm-header>
        <vm-new-header
                @created="headers.push($arguments[0])"
        ></vm-new-header>
    </div>
</template>

<script>
    import vmHeader from './header.vue'
    import vmNewHeader from './new-header.vue'
    import _ from 'lodash'

    export default {
        components: {
            vmNewHeader,
            vmHeader,
        },
        data (){
            return {}
        },
        methods: {
//            update (key, value){
//                this.flush()
//            },
//            remove (key){
//                console.log('delete')
//                delete this.headers[key]
//                this.flush()
//            },
//            flush(){
//                this.headers = _.clone(this.headers)
//                this.$emit('updated', this.headers)
//            }
        },
        props: {
            headers: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style>

</style>