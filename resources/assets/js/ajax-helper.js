import $ from 'jquery'
let token = $('meta[name=token]').prop('content')

export default function (method, url, data, context, headers){
    if(!headers){
        headers = {}
    }
    
    headers['X-CSRF-TOKEN'] = token

    return $.ajax({
        url,
        data,
        method,
        headers,
        context,
    })
}
