<template>
    <div class="navigation-tabs tabs">
        <ul>
            <li v-for="page in pages"
                :class="{'is-active': mode === page}">
                <a @click="mode = page"
                   v-text="page | capitalize"
                ></a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        watch: {
            mode (mode){
                this.$emit('changed', mode)
            }
        },
        props: {
            mode: {
                required: false,
                type: String,
            },
            pages: {
                required: true,
                type: Array,
            }
        }
    }
</script>

<style scoped>
    .navigation-tabs:not(:last-child) {
        margin-bottom: 10px;
    }
</style>