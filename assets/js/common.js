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

var storage = {
    get(key) {
        var data = localStorage.getItem(key);
        if (!data) return null;
        data = JSON.parse(data);
        return data.value;
    },
    set(key, val) {
        var data = {
            type: typeof val,
            value: val
        };
        localStorage.setItem(key, JSON.stringify(data));
    },
    remove(key) {
        localStorage.removeItem(key);
    },
    clear() {
        localStorage.clear();
    }
};

function checkLogin() {
    var token = $.cookie('TOKEN');
    if (!token) {
        return false;
    }
    var member = storage.get('MEMBER');
    if(!member || !member.id) {
        return false;
    }
    return member;
}