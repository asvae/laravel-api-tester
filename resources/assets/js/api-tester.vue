Application main page.

<template>
    <div class="api-tester-main">
        <div class="top-fixed has-shadow">
            <div class="columns">
                <nav class="nav" style="width: 400px">
                    <div class="nav-left">
                        <a class="nav-item logo" href="https://github.com/asvae/laravel-api-tester">
                            <span class="nav-icon" >API-TESTER</span>
                        </a>
                    </div>
                    <div class="nav-right">
                        <a class="nav-item"
                           title="Github"
                           href="https://github.com/asvae/laravel-api-tester"
                           target="_blank"
                        >
                            <i class="fa fa-github nav-icon"></i>
                        </a>
                        <a class="nav-item"
                           title="Wiki"
                           href="https://github.com/asvae/laravel-api-tester/wiki"
                           target="_blank"
                        >
                            <i class="fa fa-book nav-icon"></i>
                        </a>
                        <a class="nav-item"
                           title="Fork me"
                           href="https://github.com/asvae/laravel-api-tester/fork"
                           target="_blank"
                        >
                            <i class="fa fa-code-fork nav-icon"></i>
                        </a>
                        <a class="nav-item"
                           title="Issues"
                           href="https://github.com/asvae/laravel-api-tester/issues"
                           target="_blank"
                        >
                            <i class="fa fa-bug nav-icon"></i>
                        </a>
                    </div>
                </nav>
                <div class="column">
                    <vm-action-panel></vm-action-panel>
                </div>
            </div>
        </div>
        <div class="bottom">
            <div class="left-side is-full is-multiline">
                <vm-lists-block></vm-lists-block>
            </div>
            <div class="right-side">
                <vm-request-poster></vm-request-poster>
            </div>
        </div>
    </div>
</template>

<script>
    import vmListsBlock from './components/lists-block/lists-block.vue'
    import vmActionPanel from './components/action-panel/action-panel.vue'
    import vmRequestPoster from './components/edit-block/edit-block.vue'

    export default {
        components: {
            vmActionPanel,
            vmListsBlock,
            vmRequestPoster,
        },
    }
</script>

We decided to forsake all the mobile support stuff. It requires bulma @media
which requires scss in .vue file, which is not supported by phpstorm ATM.
<style scoped>
    .nav {
        background-color: transparent;
    }

    .nav-item > .nav-icon {
        transition: all ease .1s;
        color: #006679;
        text-shadow: 1px 1px 1px #3debff, -1px -1px 1px #0092a2;
    }
    .nav-item:hover > .nav-icon{
        color: #c6faff;
        text-shadow: -1px -1px 1px #3debff, 1px 1px 1px #0092a2;
    }

    .top-fixed {
        width: 100%;
        padding: 10px;
        top: 0;
        left: 0;
        position: fixed;
        background-color: #1fc8db;
        z-index: 5;
        box-shadow: 0 2px 5px rgba(0, 0, 0, .15);
    }

    .left-side {
        margin-top: 52px;
        left: 0;
        position: absolute;
        width: 400px;
        min-height: calc(100% - 52px);
        padding: 10px;
    }

    .right-side {
        margin-top: 52px;
        left: calc(400px);
        position: absolute;
        padding: 10px;
        width: calc(100% - 400px);
    }

    .logo {
        padding-left: 20px;
    }
</style>

