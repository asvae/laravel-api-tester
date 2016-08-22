<template>
    <div class="card current-route is-fullwidth">
        <header class="card-header">
            <p class="card-header-title">Route Info</p>
        </header>
        <div
            class="notification is-danger has-text-centered no-rounded-borders"
            v-if="infoError === 500"
        >
            <span><i class="fa fa-warning"> </i> Current route is broken!</span>
        </div>
        <div
            class="notification is-warning has-text-centered no-rounded-borders"
            v-if="infoError === 404"
        >
            <span><i class="fa fa-warning"> </i> No matching route found :( </span>
        </div>
        <div class="notification"  v-if="! currentRoute">
            <span> No info </span>
        </div>
        <!-- Info -->
        <div class="route-info"
             v-if="currentRoute"
        >
            <table class="table">
                <tbody>
                    <tr v-if="annotation">
                        <td colspan="2">
                            <pre  v-text="annotation"></pre>
                        </td>
                    </tr>
                    <tr>
                        <td>Methods</td>
                        <td v-text="methods"></td>
                    </tr>
                    <tr>
                        <td>Middleware</td>
                        <td>
                            <pre v-text="middleware"></pre>
                        </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td v-text="name"></td>
                    </tr>
                    <tr>
                        <td>Action</td>
                        <td v-text="action"></td>
                    </tr>
                    <tr>
                        <td colspan="2" >
                            <a v-if="! additionalInfo"
                               @click="toggleAdditionalInfo"
                               v-text="'Show additional info'"
                            ></a>
                            <a v-if="additionalInfo"
                               @click="toggleAdditionalInfo"
                               v-text="'Hide additional info'"
                            ></a>
                            <pre v-if="additionalInfo"
                                 v-text="currentRoute | json"
                            ></pre>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                additionalInfo: false,
            }
        },
        vuex: {
            getters: {
                currentRoute: (state) => state.routes.currentRoute,
                infoMode: (state) => state.infoMode,
                infoError: (state) => state.routes.infoError
            }
        },
        methods: {
            toggleAdditionalInfo(){
                this.additionalInfo = ! this.additionalInfo
            }
        },
        computed: {
            methods () {
                return this.currentRoute.methods.join(', ')
            },

            middleware () {
                let middleware = this.currentRoute.action.middleware
                if(middleware){
                    let isString = typeof middleware === 'string'
                    return isString ? middleware : middleware.join('\n')
                }

                return 'None'
            },

            annotation(){
                let annotation = this.currentRoute.annotation
                if(annotation){
                    return annotation.replace(/\n\s+/g, '\n')
                }

                return null;
            },
            name(){
                let name = this.currentRoute.action.as
                return name ? name : 'None.'
            },
            action () {
                let action = this.currentRoute.action.uses
                let isString = typeof action === 'string'
                return isString ? action : 'This route is defined by closure.'
            },
        },
    }
</script>

<style scoped>
    td:first-child {
        width: 50px;
        font-weight: 500;
    }

    iframe {
        width: 100%;
        min-height: 500px;
    }
    table.table{
        background-color: transparent;
        color: #69707a;
    }

    .notification .fa {
        vertical-align: baseline;
    }
    .card-header-title{
        color: #0092a2;
    }

    .route-info{
        overflow-x: scroll;
    }
</style>
