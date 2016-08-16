<template>
    <div class="request-editor">
        <div class="columns is-multiline" v-if="request">
            <div class="column is-full">
                <input class="input"
                       type="text"
                       placeholder="Name"
                       title="Name"
                       v-model="request.name"
                >
            </div>

            <div class="column is-full">
                <vm-navigation-tabs class="is-boxed"
                                    :pages="['data', 'headers', 'info']"
                                    :mode="mode"
                                    @changed="setMode($arguments[0])"
                ></vm-navigation-tabs>
            </div>

            <!-- Editor -->
            <div class="column is-full" v-if="mode === 'data'">
                <vm-json-editor :json="request.body"
                                style="height: 300px"
                                @changed="request.body = $arguments[0], changed = true"
                ></vm-json-editor>
            </div>

            <!-- Headers -->
            <div class="column is-full" v-if="mode === 'headers'">
                <vm-headers :headers="request.headers"
                            @updated="request.headers = $arguments[0]"
                ></vm-headers>
            </div>

            <!-- Info -->
            <vm-route-info
                    class="column is-full"
                    v-if="mode === 'info'"
            ></vm-route-info>

        </div>
    </div>
</template>

<script>
    import vmJsonEditor from '../../json-editor/json-editor.vue'
    import vmRouteInfo from './route-info.vue'
    import vmPosterNavigation from './request-editor-navigation.vue'
    import vmHeaders from '../headers/headers.vue'

    import vmNavigationTabs from '../../ligth-components/navigation-tabs.vue'

    import requestEditorData from './request-editor-data.js'


    export default {
        data: () => requestEditorData,
        components: {
            vmJsonEditor,
            vmHeaders,
            vmRouteInfo,
            vmPosterNavigation,

            vmNavigationTabs,
        },
        vuex: {
            getters: {
                mode: state => state.requestEditor.mode,
            },
            actions: {
                setMode: ({dispatch}, mode) => dispatch('SET_EDITOR_MODE', mode)
            }
        },
    }
</script>

<style scoped>
    .request-editor {
        padding-bottom: 10px;
    }
</style>