<template>
    <p class="search control has-addons">
        <input class="input is-expanded"
               type="text"
               placeholder="Search"
               title="Search"
               v-model="text"
        >
        <a class="button is-white is-primary"
           v-if="text !== ''"
           @click="reset"
        >
            <i class="fa fa-remove"></i>
        </a>
    </p>
</template>

<script>
    export default {
        data (){
            return {
                text: '',
            }
        },
        watch: {
            search (search) {
                this.text = search
            },
            text (text) {
                this.$store.dispatch('setSearch', text)
            },
        },
        methods: {
            reset(){
                this.text = ''
            }
        },
        computed: {
            search (){
                return this.$store.state.search.text
            }
        }
    }
</script>

<style scoped>
    .search{
        margin-bottom: 0;
    }
</style>