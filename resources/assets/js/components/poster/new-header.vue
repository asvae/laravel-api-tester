<template>
    <div class="new-header">
        <form class="columns"
              @submit.prevent="create"
        >
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
                <button type="submit"
                        class="button is-success is-outlined is-icon"
                >
                    <span class="icon" v-text="'+'"></span>
                </button>
            </div>
        </form>

    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'

    export default {
        data (){
            return {
                header: {
                    key: '',
                    value: '',
                },
            }
        },
        methods: {
            wipe (){
                $(this.$el).find('input').first().focus()
                this.header.key = ''
                this.header.value = ''
            },
            create (){
                this.$emit('created', _.clone(this.header))
                this.wipe()
            }
        }
    }
</script>

<style>

</style>