<template>
    <div class="api-tester-main">
        <div class="columns">
            <div class="column is-narrow">
                <api-tester-routes
                        @selected="request = $arguments[0]"
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
        }
    }
</script>

<style>
    .api-tester-main .fade-out-transition {
        transition: all .3s ease;
    }

    .api-tester-main .api-tester-poster {
        margin-left: 15px;
    }

    .api-tester-main {
        padding: 15px;
    }

    .api-tester-main .fade-out-enter,
    .api-tester-main .fade-out-leave {
        opacity: 0;
    }

    .api-tester-main .jsoneditor-menu {
        background-color: rgb(0, 188, 212);
        border: none;
    }

    .api-tester-main .jsoneditor {
        border: none;
    }
</style>

