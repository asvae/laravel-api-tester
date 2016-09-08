<template>
    <div class="request columns is-gapless is-mobile"
         :class="{selected: currentRequest === request}"
    >
        <vm-method-button
                class="is-white column is-narrow"
                @click="setAndRun"
                v-text="request.method"
        ></vm-method-button>
        <a @click="set"
           class="is-bold column"
           v-text="displayedName"
        ></a>
        <a class="column button is-small is-white is-narrow"
           @click="$store.dispatch('deleteRequest', request)"
        >
            <i class="fa fa-times"></i>
        </a>
    </div>
</template>

<script>
    import vmMethodButton from '../ligth-components/method-button.vue'

    export default {
        components: {
            vmMethodButton
        },
        computed: {
            displayedName (){
                return this.request.name ? this.request.name : this.request.path
            },
            currentRequest(){
                return this.$store.getters.currentRequest
            }
        },
        props: ['request'],
        methods: {
            set(){
                console.log(this.request)
                this.$store.dispatch('setInfo', null)
                this.$store.dispatch('setResponse', null)
                this.$store.dispatch('setCurrentRequest', this.request)
            },
            setAndRun(){
                this.set()
                this.$store.dispatch('scheduleRequest', this.request)
            }
        },
    }
</script>

<style scoped>
    .request.columns {
        margin: 0;
        border-top: 1px solid rgba(0, 0, 0, .025);
    }
    .request .button {
        border: none;
        padding: 3px 6px;
        border-radius: 0;
    }
</style>