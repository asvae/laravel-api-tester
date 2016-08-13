<template>
    <div class="response-navigation tabs">
        <ul>
            <li v-for="tab in ['data', 'headers','preview']"
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
        name: 'response-navigation',
        vuex: {
            getters: {
                mode: state => state.responseViewer.mode,
            },
            actions: {
                setMode: ({dispatch}, mode) => dispatch('SET_VIEWER_MODE', mode)
            },
        }
    }
</script>

<style scoped>

</style>