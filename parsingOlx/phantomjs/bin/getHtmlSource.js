/*
"use strict";

//Тут объявляю несколько юзерагентов, типа мы под разными браузерами заходим постоянно
var useragent = [];
useragent.push('Opera/9.80 (X11; Linux x86_64; U; fr) Presto/2.9.168 Version/11.50');
useragent.push('Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25');
useragent.push('Opera/12.02 (Android 4.1; Linux; Opera Mobi/ADR-1111101157; U; en-US) Presto/2.9.201 Version/12.02');
*/
page = require('webpage').create(),
    address;

var system = require('system');

//Проверяем аргументы, 1 - url сайта - обязательный
if (system.args.length === 0) {
    console.log('Usage: getHtmlSource.js <some URL>');
    system.exit();
}


page.onResourceRequested = function (requestData, request) {
    if ((/http:\/\/.+?\.css$/gi).test(requestData['url'])) {
        request.abort();
    }
    if (
        (/\.doubleclick\./gi.test(requestData['url'])) ||
        (/\.pubmatic\.com$/gi.test(requestData['url'])) ||
        (/yandex/gi.test(requestData['url'])) ||
        (/google/gi.test(requestData['url'])) ||
        (/gstatic/gi.test(requestData['url']))
    ) {
        request.abort();
        return;
    }
};

address = system.args[1];

page.open(address, function (status) {
    setTimeout(function() {
        console.log(page.content)
    }, 1000);



    phantom.exit();
});