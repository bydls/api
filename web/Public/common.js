function ajax(url, data, call_function, type = 'post') {
    if (data) {
        data = JSON.stringify({data: data});
    }
    let headers = '';
    if (getToken()) {
        headers = {
            'Authorization': 'Bearer ' + getToken(),
        };
    }
    $.ajax({
        type: type,
        data: data,
        contentType: "application/json;charset=utf-8",
        dataType: 'json',
        url: BJ.url.api + url,
        headers: headers,
    }).done(function (data) {

        if (data.code == 0 && typeof call_function == 'function') {
            call_function(data.data);
        } else {
            layer.msg(data.msg);
        }

    })
}

function setToken(token) {
    return sessionStorage.setItem(BJ.url.token_name, JSON.stringify(token));
}

function getToken() {
    let info = $.parseJSON(sessionStorage.getItem(BJ.url.token_name));
    if (info) {
        $('.' + BJ.url.username_class).text(info.username);
        return info.token;
    }
    return '';
}

function outLogin() {
    sessionStorage.clear();
    window.location.href = BJ.url.web;
}

$(document).on('click', '.go_this_window', function () {
    let url = $(this).data('url');
    url=url.slice(0, 1).toUpperCase() + url.slice(1)
    window.location.href = BJ.url.web + url + '.html';
})
$(document).on('click', '.go_this_window_route', function () {
    let url = $(this).data('url'), obj = $(this).attr('data-route'), params = '?';
    obj=$.parseJSON(obj);
    for (let k in obj){
        params += k + '=' + obj[k] + '&';
    }
    params = params.slice(0, params.length-1);
    url=url.slice(0, 1).toUpperCase() + url.slice(1)
    window.location.href = BJ.url.web + url + '.html' + params;
})

$(document).on('click', '.btn-reload', function () {
    location.reload();
})

function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}