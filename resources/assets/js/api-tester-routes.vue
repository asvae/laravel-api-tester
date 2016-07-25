<template>
    <div class="api-tester-routes">
        <!-- Search -->
        <form action="#" @submit.prevent class="box">
            <input class="input"
                   type="text"
                   placeholder="Search"
                   title="Search"
                   v-model="search"
            >
        </form>

        <div class="box">
            <!-- Sort order -->
            <div class="columns">
                <a v-for="column in columns"
                   class="column"
                   @click="setSorting(column)"
                   style="width: 50px"
                >
                    {{ column | uppercase }}
                    <vm-sort-orderer :value="asc"
                                     v-if="sort === column"
                                     @change="asc = $arguments[0]"
                    ></vm-sort-orderer>
                </a>
            </div>

            <aside class="menu">
                <ul class="menu-list">
                    <li v-for="route in routesToDisplay"
                        transition="fade-in"
                        class="route"
                        :class="{selected: selected === route}"
                    >
                        <a @click="onClick(route)">
                            <div class="columns">
                                <div class="column is-narrow">
                                    <button class="button is-small is-active"
                                            @click.stop="onSend(route)"
                                            v-text="route.method"
                                    ></button>
                                </div>
                                <div class="column is-bold"
                                     v-text="route.path"
                                     style="white-space: nowrap"
                                ></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </aside>

            <div v-if="! routesToDisplay.length">
                <div>
                    <div>
                        Nothing found...
                    </div>
                </div>
            </div>
        </div>

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
                        let comparingA = a[sort]
                        let comparingB = b[sort]

                        if(Array.isArray(comparingA)){
                            comparingA = comparingA.join(',')
                        }
                        if(Array.isArray(comparingB)){
                            comparingB = comparingB.join(',')
                        }

                        return comparingA.localeCompare(comparingB) * (asc ? -1 : 1)
                    })
                }
                return toDisplay
            }
        },
        methods: {
            onSend(route){
                // We pass the cloned one away and keep the original
                // in order to figure out, which one was selected.
                this.$emit('sent', _.clone(route))
                this.selected = route
            },
            onClick (route){
                let clone = _.clone(route)
                clone.method = clone.method[0]
                this.$emit('selected',clone)
                this.selected = route
            },
            updateRoutes (){
                ajaxHelper('GET', 'routes', null, this)
                        .then(function (response) {
                            let routes = response.data
                            let order = 0

                            this.routes = _.map(routes, function (route){
                                route.type = 'route'
                                route.order = order++
                                return route
                            })
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
    .route.selected {
        border-left: 2px solid rgb(255, 82, 82);
        background-color: #eef9f2;
    }
</style>
