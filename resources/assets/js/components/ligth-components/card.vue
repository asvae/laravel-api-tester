<template>
    <div class="card is-fullwidth">
        <header v-if="hasSlot('header')" class="card-header">
           <slot name="header"></slot>
        </header>
        <div v-if="hasSlot('content')" class="card-content">
            <slot name="content"></slot>
        </div>
        <slot></slot>
        <footer v-if="hasSlot('footer')" class="card-footer">
            <slot name="footer"></slot>
        </footer>
    </div>
</template>

<script>
    export default {
        watch: {
            mode (mode){
                this.$emit('changed', mode)
            }
        },
        ready(){
            console.log(this)
        },
        props: {
            mode: {
                required: false,
                type: String,
            },
        },
        methods: {
            hasSlot(name){
                return this._slotContents[name] !== undefined
            }
        }
    }
</script>

<style>

</style>