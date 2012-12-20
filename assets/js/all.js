
/* All functions */

// COOKIES

var _cookies;
function _initCookies() {
  _cookies = {};
  var ca = document.cookie.split(';');
  var re = /^[\s]*([^\s]+?)$/i;
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i].split("=");
    if(c.length == 2) {
     _cookies[c[0].match(re)[1]] = unescape(c[1].match(re) ? c[1].match(re)[1] : '');
    }
  }
}

function getCookie(name) {
  if(!_cookies) _initCookies();
  return _cookies[name];
}

function setCookie(name, value, days) {
  if(!_cookies) _initCookies();
  _cookies[name] = value;
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    expires = "; expires="+date.toGMTString();
  }
  path = window.path ? window.path : '/';
  var domain = location.host.match(/[^.]+\.[^.]+$/);
  document.cookie = name + "=" + escape(value) + expires + "; path=" + path + (domain ? '; domain=.' + domain : ';');
}


// MISC

function uniq(length)
{
    var str = '';
    for(var i=0;i<length;i++)str += String.fromCharCode(parseInt(rand(0x61,0x7a)));
    return str;
}

function rand(min, max) { return Math.random() * (max - min + 1) + min; };

function _debug(variable, str, html)
{
    str = str == undefined ? '' : str;
    if(typeof variable == 'string') str += variable + '<br/>';
    if(typeof variable == 'object')
    {
        for(var i in variable)
        {
            str += html ? ('<b>'+i+'</b>: ' + variable[i] + '<br/>') : (i + " : " + variable[i] + "\n\n");
        }
    }
    $('#debug').html(str);
    return str;
}


/* JQuery functions */

jQuery.showOverlay = function(color, opacity, zindex) {
    zindex = zindex == undefined ? 1500 : zindex;
    opacity = opacity > 1 ? 1 : opacity;
    jQuery('#ovr-stels').remove();
    jQuery('<div/>', {
            'id' :'ovr-stels', 
            'css':{
                    'position':'fixed',
                    'width':'100%',
                    'height':'100%',
                    'backgroundColor':color,
                    'opacity':opacity,
                    'zIndex':zindex, 
                    'display':'none', 
                    'left' :0, 
                    'top':0}
            }
    ).appendTo('body').show();
}

jQuery.hideOverlay = function(){
    jQuery('#ovr-stels').fadeOut(200, function(){$(this).remove()});
}

/**
*  Ajax uploading 
*  Needs ajaxupload.js to be installed
*  @param string brnUpload - dom selector name
*  @param string action - url where upload data
*  @param string inputFile - param name that will transfer filedata
*  @param jQuery object status - where results will be shown, can be  null
*  @param function onSubmit - callback before submit
*  @param function onComplete - callback when uploading completed
*  @param string with regular expression pattern - can be null , then jpg|png|jpeg|gif is default
*/
function uploader(btnUpload,  action, inputFile, status, onSubmit, onComplete, extensions, responseType)
{
     if(typeof btnUpload != 'object') btnUpload = $(btnUpload);
     if(status && typeof status != 'object') status = $(status);
     var indicator = btnUpload.parent().find('.indicator');
     return new AjaxUpload(btnUpload, 
     {  
        action: action, 
        name:   inputFile,
        responseType : responseType ? responseType : false,
        onSubmit: function(file, ext, self){ 
            var pattern = 'jpg|png|jpeg|gif';
            pattern =  extensions ?  extensions : pattern;
            if (! (ext &&  new RegExp('(' + pattern + ')$', 'i').test(ext))){  
                status ? status.text('Only files with extentions ' + pattern.replace(/\|/g, ', ') + ' are allowable') : alert('Only files with extentions ' + pattern.replace(/\|/g,', ') + ' are allowable');  
                return false;  
            } 
            if(onSubmit != undefined && typeof onSubmit == 'function'){
                    onSubmit(self);
            } 
            !status || status.text('');
            !indicator || indicator.show();  
        },  
        onComplete: function(file, response){  
            !status || status.text(''); 
            !indicator || indicator.hide(); 
            //var response = eval('('+response+')');
            if(response.error == undefined ){  
                if(onComplete != undefined && typeof onComplete == 'function'){
                    onComplete(response);
                }
            }else{  
                alert($.trim(response.error) != '' ? response.error : 'Uploading failed');
            }  
        }  
     });
}