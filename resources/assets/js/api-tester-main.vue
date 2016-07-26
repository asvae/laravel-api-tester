<template>
    <div class="api-tester-main">
        <div class="columns">
            <div class="column is-narrow">
                <vm-routes
                        @wants-send="makeRequest($arguments[0])"
                        @wants-run="$children[2].submit()"
                ></vm-routes>
            </div>
            <div class="column is-narrow">
                <vm-requests
                        @wants-send="makeRequest($arguments[0])"
                ></vm-requests>
            </div>
            <div class="column">
                <vm-poster
                        :request-data.sync="request"
                ></vm-poster>
            </div>
        </div>
    </div>
</template>

<script>
    import vmRoutes from './api-tester-routes.vue'
    import vmPoster from './api-tester-poster.vue'
    import vmRequests from './api-tester-requests.vue'


    export default {
        data (){
            return {
                request: {method: 'GET', path: '/'}
            }
        },
        components: {
            vmRoutes,
            vmPoster,
            vmRequests,
        },
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

