function baseRequest(url, data, opts) {
    var urls = url.split('/');
    opts.url = '/api/index.php?mod=' + urls[0] + '&act=' + urls[1];
    data = data || {};
    opts.data = data;
    opts.dataType = opts.dataType || 'json';
    opts.headers = opts.headers || {
        'Authenticate': $.cookie('TOKEN')
    };
    return $.ajax(opts);
}
var request = {
    get(url, data, opts) {
        opts = opts || {};
        opts.method = 'get';
        return baseRequest(url, data, opts);
    },
    post(url, data, opts) {
        opts = opts || {};
        opts.method = 'post';
        return baseRequest(url, data, opts);
    }
}
