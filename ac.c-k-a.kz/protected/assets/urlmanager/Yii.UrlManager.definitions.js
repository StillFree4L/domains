$(function() {
                Yii = Yii || {}; Yii.app = $.extend(Yii.app, {scriptUrl: '/index.php',baseUrl: '',
                hostInfo: 'http://test123.bilimal.kz'});
                Yii.app.urlManager = new UrlManager({"langParam":"ln","languages":["ru-RU","en-US"],"enablePrettyUrl":true,"enableStrictParsing":false,"rules":{"<controller:\\w+>":"<controller>","<controller:\\w+>/<action:\\w+>":"<controller>/<action>"},"suffix":null,"showScriptName":false,"routeParam":"r","cache":{"keyPrefix":"","cachePath":"/usr/home/www/test123/protected/yii/runtime/cache","cacheFileSuffix":".bin","directoryLevel":1,"gcProbability":10,"fileMode":null,"dirMode":509,"serializer":null},"ruleConfig":{"class":"yii\\web\\UrlRule"},"urlFormat":"path"});
                Yii.app.createUrl = function(route, params, ampersand)  {
                return this.urlManager.createUrl(route, params, ampersand);};
            });