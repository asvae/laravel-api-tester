<template>
    <div class="current-route">
        <div class="content">
            <div v-if="infoError" class="notification is-danger">
                Whoops... error while getting route info.
                <hr>
                {{infoError.status}}
            </div>
            <blockquote  v-if="currentRoute">
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
                            <td colspan="2" @click="additionalInfo = true">
                                <a v-if="! additionalInfo"

                                   v-text="'Show additional info'"
                                ></a>
                                <pre v-if="additionalInfo"
                                     v-text="currentRoute | json"
                                ></pre>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </blockquote>
            <iframe v-if="infoError" :srcdoc="infoError.data"></iframe>
            <blockquote  v-if="! currentRoute && infoMode !=='route' && !infoError">
                No info
            </blockquote>
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
                infoError:  (state) => state.infoError,
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
</style>
