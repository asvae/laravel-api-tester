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
    .select select {
        background-color: #1fc8db;
        border-color: #18798b;
        color: #c6faff;
    }

    .select select:focus{
        border-color: #18798b;
    }

    .select select:hover{
        border-color: #18798b ;
    }

    .select:after {
        border: 1px solid #18798b !important;
        border-right: 0 !important;
        border-top: 0 !important;
        content: " ";
        display: block;
        height: 7px;
        pointer-events: none;
        position: absolute;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
        width: 7px;
        margin-top: -6px;
        right: 16px;
        top: 50%;
    }
</style>