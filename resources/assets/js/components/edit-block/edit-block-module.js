import _ from 'lodash'
import Vue from 'vue'
let vm = new Vue
import RandExp from 'randexp'

const prepareRequest = (request) => {
    request = _.cloneDeep(request)
    // Prepare request for jquery
    // TODO rename body to data to be more consistent.

    // Prepend '/' to path if not already prepended.
    // We need all our routes to be absolute.
    // TODO Think of better ways.
    let url = request.path[0] === '/' ? request.path : '/' + request.path

    return {
        method: request.method,
        url,
        data: request.body,
        headers: request.headers,
        wheres: request.wheres,
    }
}

/**
 * @param request Request to send.
 * @param redirects Keep redirects when running recursively.
 * @param next Closure to run after request is sent.
 */
const getResult = (request, redirects, next) => {
    vm.$api.ajax(request.method, request.url, request.data, request.headers)
      .always(function (dataOrXHR, status, XHROrError) {

          let isXHR = dataOrXHR && dataOrXHR.hasOwnProperty('responseText')

          // NOTE Jquery ajax is sometimes not quite sane.
          let xhr = isXHR ? dataOrXHR : XHROrError
          let data = xhr.responseText
          try {
              data = JSON.parse(data)
          } catch (e) {
          }

          // Server-side might hint us that the response
          // triggers redirect. If that's the case,
          // we'll record redirect and move to next location.
          if (xhr.getResponseHeader('X-Api-Tester') === 'redirect') {
              redirects.push(data.data)
              let nextRequest = {
                  method: 'GET',
                  url: data.data.location,
                  data: null,
                  headers: request.headers,
              }
              getResult(nextRequest, redirects, next)
              // Early return here, request is still incomplete.
              return
          }

          let response = {
              data,
              redirects,
              isJson: typeof data !== 'string',
              headers: xhr.getAllResponseHeaders(),
              status: xhr.status,
              statusText: xhr.statusText,
          }

          next(response)
      })
}


export default {
    state: {
        isSending: false,
        scheduledRequests: [],
    },
    getters: {
        requestEditorIsSending: store => store.isSending,
        scheduledRequests: store => store.scheduledRequests,
    },
    mutations: {
        'set-is-sending' (state, value = true){
            state.isSending = value
        },
        'schedule-request' (state, request){
            state.scheduledRequests.push(request)
        },
        'shift-request' (state){
            state.scheduledRequests.shift()
        },
    },
    actions: {
        setRequestEditorIsSending: ({commit}, isSending) => commit('set-is-sending', isSending),
        scheduleRequest: ({commit}, request) => commit('schedule-request', request),
        shiftRequest: ({commit}, request) => commit('shift-request', request),
        loadCurrentRequestInfo({commit, getters}, request){
            request = prepareRequest(getters.currentRequest)

            // If we won't stick the following header server app will
            // process the request as usual and won't give any info.
            request.headers.push({key: 'X-Api-Tester', value: 'route-info'})

            // Modifies path if wheres are declared in request.
            // Otherwise, we'll send to unmodified path.
            let path = request.url
            if (request.wheres) {
                let wheres = request.wheres
                for (let index in wheres) {
                    let mocker = new RandExp(new RegExp(wheres[index]))
                    let dummy = new RegExp('{' + index + '}', 'g')

                    path = path.replace(dummy, mocker.gen())
                }
            }

            // Do sending.
            vm.$api.ajax(request.method, path, request.data, request.headers)
              .then(({data}) => {
                  commit('set-info', data)
                  commit('set-info-error', false)
              })
              .catch(({status}) => commit('set-info-error', status))
        },
        sendRequest ({commit}, request){
            request = prepareRequest(request)

            // Clear response viewer
            commit('set-response', null)
            // Spin loading icon
            commit('set-is-sending', true)

            // Apply special header so that middleware will prevent redirects.
            request.headers.push({
                key: 'X-Api-Tester',
                value: 'catch-redirect'
            })

            // When redirects occurs it triggers the same function recursively.
            getResult(request, [], response => {
                commit('set-response', response)
                commit('set-is-sending', false)
            })
        },
        saveRequest: function ({commit}, request) {
            return vm.$api_demo2.load({url: 'requests/store'}, request)
                     .then(({data}) => {
                         commit('set-current-request', data)
                     })
        },
        updateRequest: function ({commit}, request) {
            return vm.$api_demo2.load({url: 'requests/update'}, request)
                     .then(({data}) => {
                         commit('set-current-request', data)
                         commit('update-request', data)
                     })
        },
    }
}
