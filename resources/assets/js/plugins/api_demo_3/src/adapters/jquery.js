import $ from 'jquery'

export default function load(request) {
    return $.ajax(request)
}