import $ from 'jquery'

global.ENV = {
    token: $('meta[name=token]').prop('content'),
    base: $('base').prop('href'),
}