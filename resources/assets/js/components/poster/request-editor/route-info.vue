<template>
    <div class="current-route">
        <table class="table" v-if="currentRoute">
            <tbody>
            <tr>
                <td>Methods</td>
                <td v-text="methods"></td>
            </tr>
            <tr>
                <td>Middleware</td>
                <td v-text="middleware"></td>
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
                currentRoute: (state) => state.currentRoute,
            }
        },
        computed: {
            methods () {
                return this.currentRoute.methods.join(', ')
            },
            methods () {
                return this.currentRoute.action.middleware.join(', ')
            },
            action () {
                let action = this.currentRoute.action.uses
                let isString = typeof action === 'string'

                return isString ? action : 'Current route is defined by closure'
            },
            state () {
                let middleware = this.currentRoute.action.middleware
                let isString = typeof middleware === 'string'

                return isString ? middleware : middleware.join(', ')
            },
        },
    }
</script>

<style scoped>
    td:first-child {
        width: 50px;
        font-weight: 500;
    }
</style>
