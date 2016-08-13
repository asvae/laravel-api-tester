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

    export default {
        components: {
            vmNewHeader,
            vmHeader,
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