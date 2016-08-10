<template>
    <div class="poster-navigation control has-addons">
        <button class="button page"
                type="button"
                :class="{'is-primary': 'request' === mode}"
                @click="setMode('request')"
                v-text="'Request'"
        ></button>

        <button class="button page"
                type="button"
                :class="{'is-primary': 'headers' === mode}"
                @click="setMode('headers')"
                v-text="'Headers'"
        ></button>

        <button class="button page"
                type="button"
                :class="{'is-primary': 'info' === mode}"
                @click="setMode('info')"
                v-text="'Info'"
        ></button>
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