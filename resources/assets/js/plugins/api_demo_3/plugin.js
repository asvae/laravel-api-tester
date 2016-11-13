import api from './instances/api_demo_3'

export default function (Vue) {
    Vue.prototype.$api_demo_3 = api(Vue)
}

