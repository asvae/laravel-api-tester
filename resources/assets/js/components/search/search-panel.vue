<template>
    <p class="search control has-addons">
        <input class="input is-expanded"
               type="text"
               placeholder="Search"
               title="Search"
               :value="search"
               @input="monitorSearch"
        >
        <a class="button is-white is-primary"
           v-if="search !== ''"
           @click="$store.dispatch('setSearch', '')"
        >
            <i class="fa fa-remove"></i>
        </a>
    </p>
</template>

<script>
    export default {
        methods: {
            monitorSearch (e){
                this.$store.dispatch('setSearch', e.target.value)
            }
        },
        computed: {
            search (){
                return this.$store.getters.search
            }
        }
    }
</script>

<style scoped>
    .search {
        margin-bottom: 0;
    }
</style>