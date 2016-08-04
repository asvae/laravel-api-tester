<template>
    <div class="poster-navigation">

        <button class="button page"
                type="button"
                :class="{selected: 'request' === page}"
                @click="page = 'request'"
                v-text="'Request'"
        ></button>

        <button class="button page"
                type="button"
                :class="{selected: 'headers' === page}"
                @click="page = 'headers'"
                v-text="'Headers'"
        ></button>

        <button class="button page"
                type="button"
                :class="{selected: 'config' === page}"
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
    .page.selected {
        background-color: #0fb8ad;
    }

    .page {
        background-color: white;
    }
</style>