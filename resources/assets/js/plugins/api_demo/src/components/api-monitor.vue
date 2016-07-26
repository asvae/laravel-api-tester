<template>
    <div class="panel panel-info">
        <div class="panel-heading">Руты</div>
        <div class="panel-body">
            <pre v-text="$activeActions | json"></pre>
        </div>
    </div>
</template>

<script>
    import * as actions from  '../vuex/api-actions'

    export default {
        vuex: {
            getters: {
                apiDemo: state => state.apiDemo,
            },
            actions,
        },
    }
</script>

<style>

</style>
