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
                methods: (state) => state.currentRoute.methods.join(', '),
                action: (state) => state.currentRoute.action.uses,
                currentRoute: (state) => state.currentRoute,
                middleware (state) {
                    let middleware = state.currentRoute.action.middleware
                    let isString = typeof middleware === 'string'

                    return isString ? middleware : middleware.join(', ')
                },
            }
        }
    }
</script>

<style scoped>
    td:first-child {
        width: 50px;
        font-weight: 600;
    }
</style>
