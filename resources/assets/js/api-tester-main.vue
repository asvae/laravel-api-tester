<template>
    <div class="api-tester-main">
        <div class="columns">
            <div class="column is-narrow">
                <api-tester-routes
                        @wants-send="makeRequest($arguments[0])"
                        @wants-run="$children[1].submit()"
                ></api-tester-routes>
            </div>
            <div class="column">
                <api-tester-poster
                        :request-data="request"
                ></api-tester-poster>
            </div>
        </div>
    </div>
</template>

<script>
    import apiTesterRoutes from './api-tester-routes.vue'
    import apiTesterPoster from './api-tester-poster.vue'

    export default {
        data (){
            return {
                request: {method: 'GET', route: '/'}
            }
        },
        components: {
            apiTesterRoutes,
            apiTesterPoster,
        },
        methods: {
            makeRequest(route){
                this.request = {
                    method: route.methods[0],
                    path: route.path,
                }
            }
        }
    }
</script>

<style lang="scss">
    .api-tester-main {
        padding: 15px;
    }

    /* jsoneditor color fixes */
    .api-tester-main .jsoneditor-menu {
        background-color: rgb(0, 188, 212);
        border: none;
    }
    .api-tester-main .jsoneditor {
        border: none;
    }
</style>

