import Vuex from 'vuex'

const state = {
    infoMode: 'route',
    infoError: false,
}

import routes from '../components/routes/routes-module.js'
import search from '../components/search/search-module.js'
import routeInfo from '../components/edit-block/request-editor/route-info/info-module.js'
import response from '../components/edit-block/response-viewer/response-viewer-module.js'
import requestEditor from '../components/edit-block/request-editor-module.js'
import requests from '../components/requests/requests-module.js'
import history from '../components/history/history-module.js'
import request from '../components/edit-block/request-editor/request-module.js'

export default new Vuex.Store({
    strict: true,
    state,
    modules: {
        routes,
        search,
        response,
        requestEditor,
        requests,
        history,
        routeInfo,
        request,
    }
})