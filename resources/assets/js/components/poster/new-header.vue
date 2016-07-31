<template>
    <div class="new-header">
        <form class="columns"
              @submit.prevent="$emit('created', header.key, header.value)"
        >
            <div class="column is-half">
                <input class="input"
                       type="text"
                       placeholder="Key"
                       title="Key"
                       v-model="header.key"
                >
            </div>

            <div class="column is-half">
                <input class="input"
                       type="text"
                       placeholder="Value"
                       title="Value"
                       v-model="header.value"
                >
            </div>

            <button type="submit"
                    class="button is-success is-icon"
            >
                <span class="icon" v-text="'+'"></span>
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                header: {
                    key: '',
                    value: '',
                }
            }
        },
    }
</script>

<style>

</style>