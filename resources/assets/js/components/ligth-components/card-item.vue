<template>
    <div class="card-item">
        <slot></slot>
    </div>
</template>
<style scoped>
    .card-item{
        margin: 4px 0;
        border-right: 2px solid transparent;
        border-bottom: 1px solid rgba(0,0,0,.025);
    }
</style>