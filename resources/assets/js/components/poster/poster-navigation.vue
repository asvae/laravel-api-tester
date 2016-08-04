<template>
    <div class="poster-navigation">

        <button class="button page"
                type="button"
                :class="{'is-primary': 'request' === page}"
                @click="page = 'request'"
                v-text="'Request'"
        ></button>

        <button class="button page"
                type="button"
                :class="{'is-primary': 'headers' === page}"
                @click="page = 'headers'"
                v-text="'Headers'"
        ></button>

        <!-- // TODO Implement -->
        <button v-if="false"
                class="button page"
                type="button"
                :class="{'is-primary': 'config' === page}"
                @click="page = 'config'"
                v-text="'Config'"
        ></button>

    </div>
</template>

<script>
    export default {
        props: {
            page: {
                type: String,
                default: 'request',
            }
        },
        watch: {
            page (page){
                this.$emit('changed', page)
            }
        }
    }
</script>

<style scoped>

</style>