<template>
    <div class="current-route">
        <table class="table" v-if="currentRoute">
            <tbody>
            <tr>
                <td>Methods</td>
                <td v-text="currentRoute.methods.join(', ')"></td>
            </tr>
            <tr>
                <td>Middlewares</td>
                <td v-text="currentRoute.action.middleware.join(', ')"></td>
            </tr>
            <tr>
                <td>Contoller</td>
                <td v-text="currentRoute.action.controller"></td>
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
                currentRoute: state => state.currentRoute,
            },
        },
    }
</script>

<style scoped>

</style>