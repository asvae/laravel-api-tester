<template>
    <div class="search-panel">
        <input class="input"
               type="text"
               placeholder="Search"
               title="Search"
               v-model="search"
        >
    </div>
</template>

<script>
    export default {
        data (){
            return {
                search: ''
            }
        },
        watch: {
            search (search) {
                this.setSearch(search)
            },
        },
        vuex: {
            actions: {
                setSearch ({dispatch}, search){
                    dispatch('SET_SEARCH', search)
                }
            }
        }
    }
</script>

<style scoped>

</style>