<template>
    <div class="api-tester-routes">
        <form action="#" @submit.prevent="">
            <mdl-textfield :floating-label="!! search"
                           label="Search"
                           :value.sync="search"
            ></mdl-textfield>
        </form>
        <table class="mdl-data-table mdl-js-datea-table mdl-shadow--2dp"
               style='width:100%'>
            <tr>
                <td v-for="column in columns"
                    @click="setSorting(column)"
                    style="width: 50px"
                >
                    {{ column | uppercase }}
                    <vm-sort-orderer :value="asc"
                                     v-if="sort === column"
                                     @change="asc = $arguments[0]"
                    ></vm-sort-orderer>
                </td>
            </tr>
            <tr v-for="route in routesToDisplay"
                @click="onClick(route)"
                transition="fade-out"
            >
                <td colspan="3" :class="{selected: selected === route}">
                    <div>
                        <span class="method mdl-color--primary mdl-color-text--primary-contrast"
                              v-text="route.method"
                        ></span>
                        <span class="path" v-text="route.path"></span>
                    </div>
                    <div class="action" v-text="route.action"></div>
                </td>
            </tr>
            <tr v-if="! routesToDisplay.length">
                <td colspan="3">
                    <div>
                        Nothing found...
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import $ from 'jquery'
    import _ from 'lodash'
    import ajaxHelper from './ajax-helper'
    import vmSortOrderer from './sort-orderer.vue'

    export default {
        data: function () {
            return {
                selected: null,
                search: '',
                routes: [],
                sort: null,
                asc: true,
                columns: [
                    'method',
                    'path',
                    'action',
                ],
            }
        },
        components: {
            vmSortOrderer
        },
        ready (){
            this.updateRoutes()
        },
        computed: {
            routesToDisplay () {
                let toDisplay = []
                let sort = this.sort
                let asc = this.asc
                let search = this.search.toUpperCase()
                // Let's find out what to display first.
                this.routes.forEach(function (route) {
                    if (
                            route.method.join(',').toUpperCase().includes(search)
                            || route.path.toUpperCase().includes(search)
                            || route.action.toUpperCase().includes(search)
                    ) {
                        toDisplay.push(route)
                    }
                })
                // Then sort it.
                if (this.sort) {
                    toDisplay = toDisplay.sort(function (a, b) {
                        return a[sort].localeCompare(b[sort]) * (asc ? -1 : 1)
                    })
                }
                return toDisplay
            }
        },
        methods: {
            onClick (route){
                let selected =  _.clone(route)
                selected.method = selected.method[0]
                this.$emit('selected', selected)
                this.selected = _.clone(route)
            },
            updateRoutes (){
                ajaxHelper('POST', '_api-tester/routes', null, this)
                        .then(function (response) {
                            this.routes = response.data
                        })
            },
            setSorting(param){
                if (param === this.sort) {
                    // Click on same column
                    this.asc = !this.asc
                    return
                } else {
                    // Click on different column
                    this.asc = true
                    this.sort = param
                }

            }
        }
    }


</script>

<style scoped>
    table.mdl-data-table tr > td,
    table.mdl-data-table tr > th {
        text-align: left;
        cursor: pointer;
    }

    td.selected {
        border-left: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }

    .method {
        padding: 2px 5px;
    }

    .path {
        font-weight: bold;
    }

    .action {
        font-size: 90%;
    }
</style>
