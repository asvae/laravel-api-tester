<template>
    <div class="response-viewer-navigation tabs is-boxed">
        <ul>
            <li v-for="tab in ['body', 'headers','preview']"
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