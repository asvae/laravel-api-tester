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
            </tbody>
        </table>
        <pre v-text="currentRoute | json"></pre>
    </div>
</template>

<script>
    export default {
        data (){
            return {}
        },

        vuex: {
            getters: {
                methods: function (state) {
                    return state.currentRoute.methods.join(', ');
                },

                middleware: function (state) {
                    let middleware = state.currentRoute.action.middleware

                    if(typeof middleware === 'string'){
                        return middleware
                    }

                    return middleware.join(', ')
                },

                action: function(state){
                    return state.currentRoute.action.uses
                },

                currentRoute: function(state){
                    return state.currentRoute
                }
            }
        }
    }
</script>

<style scoped>

</style>
