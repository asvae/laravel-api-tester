import $ from 'jquery'

global.ENV = {
    token: $('meta[name=token]').prop('content'),
    base: $('base').prop('href'),
    firebaseToken: $('meta[name=firebaseToken]').prop('content'),
    firebaseSource: $('meta[name=firebaseSource]').prop('content'),
}



