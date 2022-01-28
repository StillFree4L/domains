window.attachEvent('onload', mkwidth);
window.attachEvent('onresize', mkwidth);


function mkwidth(){
var minwidth = document.getElementById("container").currentStyle['min-width'].replace('px', '');
var maxwidth = document.getElementById("container").currentStyle['max-width'].replace('px', '');
document.getElementById("container").style.width = document.documentElement.clientWidth < minwidth ? minwidth+"px" : (document.documentElement.clientWidth > maxwidth ? maxwidth+"px" : "100%");
};