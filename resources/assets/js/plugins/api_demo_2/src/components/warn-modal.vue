<template>
    <div class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content"
                 v-if="currentWarn"
            >
                <div class="modal-header panel-heading">
                    <h4 class="modal-title"
                        v-text="$t('notification')"
                    ></h4>
                </div>
                <div class="modal-body">
                    <div v-text="currentWarn.message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-danger"
                            v-text="$t('confirm')"
                            @click="confirm"
                    ></button>
                    <button type="button"
                            class="btn btn-default"
                            v-text="$t('cancel')"
                            data-dismiss="modal"
                    ></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data (){
            return {
                currentWarn: null,
                routes: this.$accessor.urlsToWarn,
            }
        },
        ready() {
            $(this.$el).on('hide.bs.modal', function () {
                this.deny()
            }.bind(this))
        },
        props: {
            text: {
                type: String,
                required: false,
            }
        },
        watch: {
            routes (routes){
                this.currentWarn = routes.length ? routes[0] : null
            },
            currentWarn (warn){
                $(this.$el).modal(warn ? 'show' : 'hide')
            }
        },
        methods: {
            confirm (){
                this.currentWarn.confirm()
                state.urlsToWarn.$remove(this.currentWarn)
            },
            deny (){
                state.urlsToWarn.$remove(this.currentWarn)
            },
        }
    }
</script>

<style scoped>
    .modal-header {
        background-color: #d9534f;
    }

    .modal-title {
        color: white;
    }
</style>