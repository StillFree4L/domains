var page = require('webpage').create(),
    address;

var system = require('system');

//Проверяем аргументы, 1 - url сайта - обязательный
if (system.args.length === 0) {
    console.log('Usage: getHtmlSource.js <some URL>');
    system.exit();
}

address = system.args[1];

page.open(address, function (status) {
    console.log(page.content); // Выводим страницу в консоль, php заберет данные в переменную
    phantom.exit();
});