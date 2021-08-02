/**
 * Class for Social Networks connections
 */
/*
var VKI = new CustomEvent("VKI");
var FBI = new CustomEvent("FBI");
var MruI = new CustomEvent("MruI");

SN = {    
    
    config : {
//        VK_apiId: 3542879,
//        VK_apiUrl: "http://vkontakte.ru/js/api/openapi.js",
//        FB_apiId: 163726677120917,
//        FB_apiUrl: "http://connect.facebook.net/en_US/all.js",
//        FB_apiSiteUrl : "gyap.com",
//        Mru_apiId : 702662,
//        Mru_apiPrivateKey: "44312545e11168c04cb4ac53fd73efa9",
//        Mru_apiUrl : "http://cdn.connect.mail.ru/js/loader.js",  
        VK_apiId: 3428826,
        VK_apiUrl: "http://vkontakte.ru/js/api/openapi.js",
        FB_apiId: 374653749308159,
        FB_apiUrl: "http://connect.facebook.net/en_US/all.js",
        FB_apiSiteUrl : "thegyap.com",
        Mru_apiId : 699002,
        Mru_apiPrivateKey: "1e6964a976deb846a6e1df2631a6bf3f",
        Mru_apiUrl : "http://cdn.connect.mail.ru/js/loader.js"
    },
    
    // VK.com
    VK : {
        i : null,
        connected : false,
        connectionInfo : null,
        userInfo: null,
        init : function(callback) {            
            $.getScript(SN.config.VK_apiUrl).done(
                function() {
                VK.init({
                  apiId: SN.config.VK_apiId
                });
                SN.VK.i = VK;
                SN.VK.i.Auth.getLoginStatus(function(response) {
    
                    if (response.session)
                    {
                        SN.VK.connected = true;
                        SN.VK.connectionInfo = response;
                    } else {
                        SN.VK.connected = false;
                    }
    
                    if (typeof callback != 'undefined') {
                        callback(response);
                    }
                    
                    document.dispatchEvent(VKI);
                                    
                });
            });            
        },
        login : function(callback)
        {
            SN.VK.i.Auth.login(function(response) {
            if (response.session) {                    
                     
                SN.VK.connected = true;
                SN.VK.connectionInfo = response;
                     
            } else {

                this.authError();

            }
                if (typeof callback != 'undefined') {
                    callback(response);
                }
            });
        }, 
        getUserInfo : function(callback)
        {
            
            if (SN.VK.connected) {
                SN.VK.i.Api.call('users.get', {
                    uids:SN.VK.connectionInfo.session.mid,
                    fields:"uid,first_name, last_name, sex, bdate,city, country, photo, photo_medium, photo_big, photo_rec,"
                }, function (response) {
                    
                    SN.VK.i.Api.call('places.getCityById', {
                        cids:response.response[0].city
                    }, function(r) {
                        response.response[0].cityName = r.response[0].name;
                        SN.VK.i.Api.call('places.getCountryById', {
                        cids:response.response[0].country
                        }, function(r) {
                            response.response[0].countryName = r.response[0].name;
                            SN.VK.userInfo = response.response[0];
                            
                            if (typeof callback != 'undefined') {
                                callback(response.response[0]);
                            }
                        });
                    });                    
                });
            } else {            
                if (typeof callback != 'undefined') {
                    callback(SN.VK.userInfo);
                }
            }
            
        },
        sendInvite : function(params, callback)
        {

            var to_c = 0;
            if (typeof params.to == 'undefined') postOnWall(0);
            else
            if (params.to[to_c]) postOnWall(params.to[to_c]);
            function postOnWall(owner_id)
            {
                
                var p = {
                    message:params.message + '(' + params.link + ')'
                }
                if (owner_id > 0) {
                    p.owner_id = owner_id;
                }
                
                SN.VK.i.Api.call("wall.post",
                p
                , function(response) {
                    
                    if (response.response.post_id > 0 && typeof callback != 'undefined') {
                        callback(true);
                    }
                    
                    to_c++;
                    if (typeof params.to != 'undefined' && params.to[to_c])
                    {
                        postOnWall(to[to_c]);
                    }
                });
            }
            
        }
    },
    
    FB : {
        i : null,
        connected : false,
        connectionInfo : null,
        userInfo: null,
        init : function(callback) {            
            $.getScript(SN.config.FB_apiUrl).done(
                function() {
                FB.init({
                    appId:SN.config.FB_apiId, // App ID
                    channelUrl:SN.config.FB_apiSiteUrl, // Channel File
                    status:true, // check login status
                    cookie:true, // enable cookies to allow the server to access the session
                    xfbml:true  // parse XFBML
                });                
                SN.FB.i = FB;
                SN.FB.i.getLoginStatus(function(response) {
    
                    if (response.status == "connected")
                    {
                        SN.FB.connected = true;
                        SN.FB.connectionInfo = response;
                    } else {
                        SN.FB.connected = false;
                    }
    
                    if (typeof callback != 'undefined') {
                        callback(response);
                    }
                      
                    document.dispatchEvent(FBI);
                     
                });
            });            
        },
        login : function(callback)
        {
            SN.FB.i.login(function(response) {
            if (response.authResponse) {                    
                     
                SN.FB.connected = true;
                SN.FB.connectionInfo = response;
                     
            } else {

                SN.authError();

            }
                if (typeof callback != 'undefined') {
                    callback(response);
                }
            });
        },
        getUserInfo : function(callback)
        {
            if (SN.FB.connected) {
                SN.FB.i.api('/me', {
                  fields:"name, id, location, gender, last_name, first_name, email, birthday"  
                }, function (response) {
                    
                    SN.FB.i.api('/me/picture', {
                        type:'large'
                    }, function(r) {
                    
                        response.photo = r.data.url;
                        response.signedRequest = SN.FB.connectionInfo.authResponse.signedRequest;

                        SN.FB.userInfo = response;

                        if (typeof callback != 'undefined') {
                            callback(response);
                        }
                    });
                });
            } else {              
                if (typeof callback != 'undefined') {
                    callback(SN.FB.userInfo);
                }
            }
        },
        sendInvite : function(params, callback)
        {
            
            var to_c = 0;
            if (typeof params.to == 'undefined') postOnWall(0);
            else 
            if (params.to[to_c]) postOnWall(params.to[to_c]);
            function postOnWall(owner_id)
            {
                
                var p = {
                    method:'feed',
                    description:params.message,                    
                    link:params.link
                }
                if (owner_id > 0) {
                    p.to = owner_id;
                }
                
                SN.FB.i.ui(p, function(response) {
                    
                     if (typeof response.post_id != 'undefined' && typeof callback != 'undefined') {
                        callback(true);
                    }
                    
                    to_c++;
                    if (typeof params.to != 'undefined' && params.to[to_c])
                    {
                        postOnWall(to[to_c]);
                    }
                });
            }

        }
    },
    
    Mru : {
        i : null,
        connected : false,
        connectionInfo : null,
        userInfo: null,
        init : function(callback) {            
            $.getScript(SN.config.Mru_apiUrl).done(
                function() {
                mailru.loader.require('api', function() {                
                    mailru.connect.init(SN.config.Mru_apiId, SN.config.Mru_apiPrivateKey);
                    mailru.events.listen(mailru.connect.events.logout, function(){
                            this.Mru.connected = false;
                            this.Mru.connectionInfo = null                            
                    });                
                    SN.Mru.i = mailru;
                    SN.Mru.i.connect.getLoginStatus(function(response) {

                        if (response.is_app_user == 1)
                        {
                            SN.Mru.connected = true;
                            SN.Mru.connectionInfo = response;
                        } else {
                            SN.Mru.connected = false;
                        }

                        if (typeof callback != 'undefined') {
                            callback(response);
                        }

                        document.dispatchEvent(MruI);

                    });
                });  
                });
        },
        login : function(callback)
        {
            mailru.events.listen(mailru.connect.events.login, function(response){
                    
                    if (response.is_app_user == 1)
                    {
                        SN.Mru.connected = true;
                        SN.Mru.connectionInfo = response;
                        
                    } else {
                        
                        SN.authError();
                        
                        SN.Mru.connected = false;
                    }

                    if (typeof callback != 'undefined') {
                        callback(response);
                    }
                    
                    
            });   
            
            SN.Mru.i.connect.login();
        },        
        getUserInfo : function(callback)
        {
            if (SN.Mru.connected) {
                SN.Mru.i.common.users.getInfo(function (response) {
                    SN.Mru.userInfo = response[0];
                    
                    if (typeof callback != 'undefined') {
                        callback(response[0]);
                    }
                });
            } else {            
                if (typeof callback != 'undefined') {
                    callback(SN.VK.userInfo);
                }
            }
            
        },
        sendInvite : function(params, callback)
        {
            
            var to_c = 0;
            if (typeof params.to == 'undefined') postOnWall(0)
            else 
            if (params.to[to_c]) postOnWall(params.to[to_c]);
            function postOnWall(owner_id)
            {
                
                var p = {
                   text:params.message,                    
                   action_links: [
                      {text: params.link, href: params.link}
                   ]   
                }
                if (owner_id > 0) {
                    p.uid = owner_id;
                }
                
                SN.Mru.i.common.guestbook.post(p);
            }

            SN.Mru.i.events.listen(mailru.common.events.guestbookPublish, function(event) {
                console.log(event);
                to_c++;
                if (typeof params.to != 'undefined' && params.to[to_c])
                {
                    postOnWall(to[to_c]);
                }
            });            

        }
        
        
    },

    authError : function()
    {
        $.jGrowl("Авторизация не пройдена", {
            theme:"error"
        });
    }
    
}
    */