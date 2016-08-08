<template>
    <div class="request-type-select select">
        <select v-model="method">
            <option v-for="option in options"
                    :value="option"
                    v-text="option"
            ></option>
        </select>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                options: [
                    'POST',
                    'GET',
                    'DELETE',
                    'PUT',
                    'PATCH',
                    'OPTIONS',
                ]
            }
        },
        props: {
            method: {
                type: String,
                require: true
            }
        },
        watch: {
            method (method){
                this.$emit('changed', method)
            }
        }
    }
</script>

<style scoped>

</style>