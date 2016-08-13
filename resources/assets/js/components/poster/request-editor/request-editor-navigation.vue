<template>
    <div class="request-editor-navigation tabs is-boxed">
        <ul>
            <li v-for="tab in ['body','headers','info']"
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