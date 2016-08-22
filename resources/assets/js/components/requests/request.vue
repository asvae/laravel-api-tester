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
           @click="deleteRequest(request)"
        >
            <i class="fa fa-times"></i>
        </a>
    </div>
</template>

<script>
    import {
            setCurrentRequest,
            scheduleRequest,
            setRequestInfo,
            setResponse,
    } from '../../vuex/actions.js'
    import vmMethodButton from '../ligth-components/method-button.vue'

    export default {
        components: {
            vmMethodButton
        },
        computed: {
            displayedName (){
                return this.request.name ? this.request.name : this.request.path
            }
        },
        data (){
            return {}
        },
        methods: {
            setAndRun(){
                this.set()
                this.scheduleRequest(this.request)
            },
            set(){
                this.setRequestInfo(null)
                this.setResponse(null)
                this.setCurrentRequest(this.request)
            }
        },
        vuex: {
            actions: {
                setCurrentRequest,
                scheduleRequest,
                setRequestInfo,
                setResponse,
                deleteRequest ({dispatch}, request) {
                    this.$api_demo2.load({url: 'requests/destroy'}, request)
                        .then(() => {
                            dispatch('DELETE_REQUEST', request)
                        })
                },
            },
        },
        props: {
            request: {
                type: Object,
            }
        }
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