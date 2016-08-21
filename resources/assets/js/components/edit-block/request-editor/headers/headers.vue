<template>
    <div class="headers">
        <vm-header
                v-for="header in headers"
                :header="header"
                @removed="headers.$remove(header)"
                @enter="addNewHeader"
        ></vm-header>
        <div class="columns is-centered" v-if="! headers.length">
            <div class="column content">
                <blockquote>No headers present</blockquote>
            </div>
        </div>
        <div class="columns is-centered">
            <div class="column is-narrow">
                <button type="button"
                        class="button is-success is-icon"
                        @click="addNewHeader"
                >
                    <span class="icon">+</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import vmHeader from './header.vue'

    export default {
        components: {
            vmHeader,
        },
        props: {
            headers: {
                type: Array,
                required: true
            }
        },
        methods: {
            addNewHeader (){
                this.headers.push({key: '', value: ''})
            }
        }
    }
</script>

<style scoped>
    .button {
        width: 60px;
    }
    blockquote {
        padding: 6px 12px;
    }
    .columns {
        margin: 0;
    }
</style>