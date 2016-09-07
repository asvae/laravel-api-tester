<template>
    <div class="card current-route is-fullwidth">
        <header class="card-header">
            <p class="card-header-title">Route Info
                <span v-if="! expanded" class="header-action" v-text="action"></span>
            </p>
            <a class="button is-medium is-white"
               @click="expanded = ! expanded"
            >
                <i class="fa" :class="expanded? 'fa-minus' : 'fa-plus'"></i>
            </a>
        </header>
        <div v-if="expanded">
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
            <div
                    class="notification is-warning has-text-centered no-rounded-borders"
                    v-if="infoError === 405"
            >
                <span><i class="fa fa-warning"> </i> Method not allowed :( </span>
            </div>
            <div class="notification" v-if="! info">
                <span> No info </span>
            </div>
            <!-- Info -->
            <div class="route-info"
                 v-if="info"
            >
                <table class="table">
                    <tbody>
                        <tr v-if="annotation">
                            <td colspan="2">
                                <pre v-text="annotation"></pre>
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
                            <td colspan="2">
                                <a v-if="! additionalInfo"
                                       @click="toggleAdditionalInfo"
                                       v-text="'Show additional info'"
                                ></a>
                                <a v-if="additionalInfo"
                                       @click="toggleAdditionalInfo"
                                       v-text="'Hide additional info'"
                                ></a>
                                <pre v-if="additionalInfo"
                                        v-text="info | json"
                                ></pre>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                additionalInfo: false,
                expanded: true,
            }
        },
        methods: {
            toggleAdditionalInfo(){
                this.additionalInfo = !this.additionalInfo
            },
        },
        computed: {
            info (){
                return this.$store.getters.info
            },
            infoError (){
                return this.$store.getters.infoError
            },
            methods () {
                return this.info.methods.join(', ')
            },
            middleware () {
                let middleware = this.info.action.middleware
                if (middleware) {
                    let isString = typeof middleware === 'string'
                    return isString ? middleware : middleware.join('\n')
                }

                return 'None'
            },
            annotation(){
                let annotation = this.info.annotation
                if (annotation) {
                    return annotation.replace(/\n\s+/g, '\n')
                }

                return null;
            },
            name(){
                let name = this.info.action.as
                return name ? name : 'None.'
            },
            action () {
                let action = this.info.action.uses
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

    table.table {
        background-color: transparent;
        color: #69707a;
    }

    .notification .fa {
        vertical-align: baseline;
    }

    .card-header-title {
        color: #0092a2;
    }

    .route-info {
        overflow-x: scroll;
    }
    .header-action{
        font-weight: 200;
        padding: 0 10px;
    }
</style>
