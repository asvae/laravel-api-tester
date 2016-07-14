import $ from 'jquery'
let token = $('meta[name=token]').prop('content')

export default function (method, url, data, context){
    return $.ajax({
        url,
        data: JSON.stringify(data),
        method,
        headers: {
            'X-CSRF-TOKEN': token
        },
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        context,
    })
}