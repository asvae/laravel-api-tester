import $ from 'jquery'
let token = $('meta[name=token]').prop('content')

export default function (method, url, data, context){
    return $.ajax({
        url,
        data,
        method,
        headers: {
            'X-CSRF-TOKEN': token
        },
        context,
    })
}