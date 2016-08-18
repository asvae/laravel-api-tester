import $ from 'jquery'

global.ENV = {
    token: $('meta[name=token]').prop('content'),
    base: $('base').prop('href'),
    firebaseToken: $('meta[name=firebaseToken]').prop('content'),
    firebaseSource: $('meta[name=firebaseSource]').prop('content'),

    getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ))
        return matches ? decodeURIComponent(matches[1]) : undefined
    }
}



