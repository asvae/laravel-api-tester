<template>
    <div class="poster-navigation tabs">
        <ul>
            <li v-for="tab in ['data','headers','info']"
                :class="{'is-active': tab === mode}"
            >
                <a @click="setMode(tab)"
                   v-text="tab | capitalize"
                ></a>
            </li>
        </ul>
    </div>
</template>

<script>


    export default {
        vuex: {
            getters: {
                mode: state => state.requestEditor.mode,
            },
            actions: {
                setMode: ({dispatch}, mode) => dispatch('SET_EDITOR_MODE', mode)
            },
        }
    }
</script>

<style scoped>

</style>