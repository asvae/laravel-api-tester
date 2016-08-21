<template>
    <div>
        <p class="search control has-addons">
            <input class="input is-expanded"
                   type="text"
                   placeholder="Search"
                   title="Search"
                   v-model="text"
            >
            <a class="button is-white is-primary"
               v-if="text !== ''"
               @click="reset()"
            >
                <i class="fa fa-remove"></i>
            </a>
        </p>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                text: ''
            }
        },
        watch: {

            // two way binding
            search (search) {
                this.text = search
            },
            text (text) {
                this.setSearch(text)
            },
        },
        vuex: {
            getters:{
                search: (state) => state.search.search
            },
            actions: {
                setSearch ({dispatch}, search){
                    dispatch('SET_SEARCH', search)
                },
            }
        },
        methods: {
            reset(){
                this.text = ''
            }
        }
    }
</script>

<style scoped>
    .search{
        width: 100%;
    }
</style>