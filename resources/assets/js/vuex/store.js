import Vuex from 'vuex'

const state = {
    infoMode: 'route',
    infoError: false,
}

import routes from '../components/routes/routes-module.js'
import search from '../components/search/search-module.js'
import routeInfo from '../components/edit-block/request-editor/route-info/info-module.js'
import response from '../components/edit-block/response-viewer/response-viewer-module.js'
import editBlock from '../components/edit-block/edit-block-module.js'
import requests from '../components/requests/requests-module.js'
import history from '../components/history/history-module.js'
import requestEditor from '../components/edit-block/request-editor/request-editor-module.js'

export default new Vuex.Store({
    strict: true,
    state,
    modules: {
        routes,
        search,
        response,
        editBlock,
        requests,
        history,
        routeInfo,
        requestEditor,
    }
})