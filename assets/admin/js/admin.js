/**
*  This library is intended for management tool and can be used for administrative purposes only.
*  @name: manager.js
*  @author: Igor Shvartsev
*  @version 1.0.1
*  @date 01 Dec, 2010
*  @category jQuery plugin 
*  @dependence  jQuery ver.1.4.2 
*  @Copyright (c) 2010 Igor Shvartsev (genryh@yahoo.com)
*/

// every selector which is assigned for available plugins should contain attribut id formated like id="xx-231231"
// where xx are two any chars, '-' - separator, 231231 - any digits

if(typeof(jQuery) != undefined)
{
    //jQuery.noConflict();
    (function($){
        $(function(){
            var url = window.url ? window.url : '/';
/**
*  Plugin 'qeditor'
*  Allows edit content which are shown on the page. Every content can be assigned to correspondent table field of Database to be edited.
*  It uses third party overlay plugin (jquery.tools.min.js) and CKEditor if it is installed.
*/
            $.fn.qeditor = function(settings){
                var config = {
                    s       : '',       // session variable
                    overlay : null,     // overlay object ( jquery.tools.min.js is needed to be installed )
                    model   : '',       // name of table
                    inputFields : [],   // objects array of table fields as text inputs for editing (f.e [{name:'title', label:'Title'}, {name:'auth', label:'Author'}...])
                    textField   : {},   // main text field which can be edited as rich text {name:'message',label:'Text'} ( if CKeditor installed then  will be placed in it )
                    textWidth   : '98%', // width of textarea
                    textHeight  : '300', // height of textarea
                    save        : 'Save',// text of Save button
                    getAction   : 'data.php?req=mngget',  // ajax action of getting data
                    saveAction  : 'data.php?req=mngsave', // ajax action for saving data
                    onSave      : function(el, data){}    // callback
                };
                config = $.extend(true, config, settings);
                return this.each(function(){
                    if ( !$.isPlainObject( config.overlay )){
                        var popup = $('<div/>',{'id':'edit-overlay',css:{'display':'none','position':'absolute','width':'640px','padding':'35px','font-size':'12px','background-image':'url(images/manager/ovrwhite.png)','height':'480px','z-index':'1000','overflow':'hidden'}}).appendTo('body');
                        $('<img/>',{'id':'load-indicator','src':'images/manager/loading.gif','css':{'border':'none','position':'absolute','bottom':'18px','left':'34px','display':'none'}}).appendTo(popup);
                        if (typeof $.prototype.overlay == 'function'){
                             config.overlay = $('#edit-overlay').overlay({
                                    expose: {
                                        color: '#fff',
                                        loadSpeed: 200,
                                        opacity: 0.5
                                    },
                                    effect: 'apple', 
                                    api   : true,
                                    onClose: function(){
                                        if (config.fck){
                                             config.fck.destroy();
                                             config.fck = null;
                                        }
                                        this.getOverlay().find(':not(.close)').remove();
                                    }
                             });
                        }else{
                            alert('jquery.tools.min.js is not installed to make edit overlay possible');
                            return false;;
                        }
                    }else if (!config.overlay || !config.overlay.getTrigger()){
                        alert('Your object is not instance of overlay object');
                        return false;
                    }
                    var $el = $(this);
                    if ($el.attr('id') == undefined){
                        alert('Attribute Id is not specified for selectors');
                        return false;
                    }
                    
                    var id = $el.attr('id').substr(3);
                    $el.click(function(){
                        $.getJSON(config.getAction,{'s':config.s,'id':id,'model':config.model},function(data){
                            var ovr = config.overlay.getOverlay();
                            var form = $('<form/>').appendTo(ovr);
                            if (data.status == undefined){
                                $('<div/>',{'class':'itemid'}).text(id).appendTo(form);
                                $.each(config.inputFields, function(i,item){
                                    $('<p><label>'+item.label+'</label><br /><input type="text" name="data['+item.name+']"/></p>').appendTo(form);
                                });
                                if (config.textField.name != undefined){
                                    $('<p><label>'+config.textField.label+'</label><br/><textarea id="texteditor-'+id+'" name="data['+config.textField.name+']"></textarea></p>').appendTo(form);
                                }
                                $.each(data.result, function(i,val){
                                    $('input[name="data['+i+']"],textarea[name="data['+i+']"]').val(val);
                                });
                                if(typeof CKEDITOR != 'undefined'){
                                    config.fck = CKEDITOR.replace( 'texteditor-' + id,{'height':config.textHeight, 'width':config.textWidth, toolbar:'76GF998H'} );
                                }
                                $('<a/>', {'class':'save btn'}).text(config.save).wrap('<p/>').click(function(){
                                    if (typeof CKEDITOR != 'undefined'){
                                        form.find('textarea').val(config.fck.getData());
                                    }
                                    $.getJSON(config.saveAction,'s='+config.s+'&id='+id+'&m='+config.model+'&'+form.serialize(),function(data){
                                        if (data.status == undefined){
                                            $.each(data.result, function(i,val){data.result[i] = val.replace(/\\"/g,'')});
                                            config.onSave($el, data.result);
                                            ovr.find('.close').click();
                                        }else{
                                            $('<h3/>',{'css':{'margin-top':'100px','text-alight':'center','color':'#777'}}).text(data.status).appendTo(ovr);
                                        }
                                    });
                                    return false;
                                }).appendTo(form);
                            }else{
                                $('<h3/>',{'css':{'margin-top':'100px','text-alight':'center','color':'#777'}}).text(data.status).appendTo(ovr);
                            }
                            config.overlay.load();
                            ovr.find('.close').css({'position':'absolute','top':'5px','right':'5px','cursor':'pointer','height':'35px','width':'35px','background-image':'url(images/manager/close.png)'});
                    });
                });           
            });
            } 

/**
*  Plugin 'qdelete'.
*  Allows delete selected item 
*/
            $.fn.qdelete = function(settings){
                var config = {
                   s            : '',                       // session variable
                   model        : '',                       // table name
                   deleteAction : 'data.php?req=mngdelete', // ajax action for deleting data
                   onDelete     : function(el, data){},     // callback
                   warn         : 'Are you sure delete this item?'
                };
                config = $.extend(true, config, settings);
                return this.each(function(){
                    var $el = $(this);
                    if ($el.attr('id') == undefined){
                        alert('Attribute Id is not specified for selectors');
                        return false;
                    }
                    var id = $el.attr('id').substr(3);
                    $el.click(function(){
                        if(confirm(config.warn)){
                            $.getJSON(config.deleteAction,{'s':config.s,'id':id,'m':config.model},function(data){
                                if (data.status == undefined){
                                    config.onDelete($el, data);
                                }else{
                                    alert(data.status);
                                }
                            });
                        }
                    });
                });
            }
            
/**
*  Plugin 'qsend'
*  Allows send message to user
*  Selected selector should have attribute id  with userID
*/
            $.fn.qsend = function(settings){
                var config = {
                    title   : 'Send message',
                    s       : '',       // session variable
                    overlay : null,     // overlay object ( jquery.tools.min.js is needed to be installed )
                    model   : '',       // name of table,
                    prive   : {lable:'Private', value:'private', enabled:true}, // private message option
                    email   : {lable:'By email', value:'email', enabled:true},  // by email option
                    sms     : {lable:'Sms', value:'sms', enabled:false},        // by sms option
                    subjectLabel : 'Subject',                 // subject label
                    msgLabel     : 'Message',                 // message label
                    sendAction   : 'data.php?req=mngsend',    // ajax send action
                    getUserAction: 'data.php?req=mnguser',    // ajax get user data action
                    textWidth   : '98%',                      // width of textarea
                    textHeight  : '230',                      // height of textarea
                    btnLabel    : 'Send',
                    onSend      : function(el, data){}
                };
                config = $.extend(true, config, settings);
                return this.each(function(){
                    if ( !$.isPlainObject( config.overlay )){
                        var popup = $('<div/>',{'id':'send-overlay',css:{'display':'none','position':'absolute','width':'640px','padding':'35px','font-size':'12px','background-image':'url(images/manager/ovrwhite.png)','height':'480px','z-index':'1000','overflow':'hidden'}}).appendTo('body');
                        if (typeof $.prototype.overlay == 'function'){
                             config.overlay = $('#send-overlay').overlay({
                                    expose: {
                                        color: '#fff',
                                        loadSpeed: 200,
                                        opacity: 0.5
                                    },
                                    effect: 'apple', 
                                    api   : true,
                                    onLoad : function(){
                                        this.getOverlay().find('input[name="subject"]').focus();
                                    },
                                    onClose: function(){
                                        if (config.fck){
                                             config.fck.destroy();
                                             config.fck = null;
                                        }
                                        this.getOverlay().find(':not(.close)').remove();
                                    }
                             });
                        }else{
                            alert('jquery.tools.min.js is not installed to make edit overlay possible');
                            return false;;
                        }
                    }else if (!config.overlay || !config.overlay.getTrigger()){
                        alert('Your object is not instance of overlay object');
                        return false;
                    }
                
                    var $el = $(this);
                    if ($el.attr('id') == undefined){
                        alert('Attribute Id is not specified for selectors');
                        return false;
                    }
                    var id = $el.attr('id').substr(3);
                    $el.click(function(){
                          var res = $.ajax({
                                    async : false,
                                    data  : {'id':id,'s':config.s},
                                    type  : 'GET',
                                    url   : config.getUserAction}).responseText; 
                          if ($.trim(res) == ''){
                              alert('Undefined user action');
                              return false;
                          }
                          res = eval('(' + res + ')');
                          if( res.status != undefined ){
                              alert(res.status);
                              return false;
                          }          
                          var ovr = config.overlay.getOverlay();
                          var form = $('<form/>').appendTo(ovr);
                          $('<h1/>').text(config.title).appendTo(form);
                          var to = $('<div/>',{'class':'to','css':{'float':'left','width':'50%'}}).appendTo(form);
                          $('<p/>').html('user ID : <span>'+id+'</span>').appendTo(to);
                          $('<p/>').html('To : <span>'+res.result.login+'</span>').appendTo(to);
                          $('<p/>',{'css':{'height':'20px'}}).html('<span class="email" style="display:none;">'+res.result.email+'<span>').appendTo(to);
                          var ul = $('<ul/>',{'class':'sendtype','css':{'list-style':'none','float':'right','width':'20%'}}).appendTo(form);
                          var options = [];
                          options.push(config.email);
                          options.push(config.prive);
                          options.push(config.sms);
                          var t = 0;
                          $.each(options, function(i, item){
                              if(item.enabled){
                                var li = $('<li/>',{'css':{'overflow':'hidden'}});
                                $('<input/>',{'type':'radio','name':'type','css':{'float':'left','width':'16px','height':'16px','margin-top':'0px'},'value':item.value}).appendTo(li);
                                $('<label/>',{'class':'lbl','css':{'float':'left','margin-left':'8px'}}).text(item.lable).appendTo(li);
                                li.appendTo(ul);
                                t++;
                              }
                          });
                          if(!t){
                              alert('You did not make enabled at least one sending type (prive, email, sms)');
                              return false;
                          }else{
                              $('input[name="type"]').change(function(){
                                  var option = $(this);
                                  if(option.val()== config.email.value){
                                     // form.find('.subject').show().find('input[name="subject"]').focus();
                                      form.find('.email').show();
                                  }else{
                                     // form.find('.subject').hide();
                                      form.find('.email').hide();
                                  }
                              }).eq(0).attr('checked','checked').change();
                          }
                          $('<p style="clear:both;" class="subject"><label>'+config.subjectLabel+'</label><br /><input type="text" name="subject" /></p>').appendTo(form);
                          $('<p style="clear:both;"><label>'+config.msgLabel+'</label><br /><textarea id="sendmsg-'+id+'" name="message"></textarea></p>').appendTo(form);
                          var textarea = $('#sendmsg-'+id);
                          textarea.css({'width':config.textWidth, 'height':config.textHeight});
                          $('<a/>', {'class':'save btn'}).text(config.btnLabel).wrap('<p/>').click(function(){
                                if (typeof CKEDITOR != 'undefined'){
                                    textarea.val(config.fck.getData());
                                }
                                if($.trim(form.find('input[name="subject"]').val()) == ''){
                                    alert('Subject field is empty');
                                    return false;
                                 }
                                /*
                                if($.trim(form.find('input[name="subject"]').val()) == '' && form.find('input[name="type"]:checked').val() == config.email.value){
                                    alert('Subject field is empty');
                                    return false;
                                }else if($.trim(textarea.val()) == '' && form.find('input[name="type"]:checked').val() != config.email.value){
                                    alert('Your message is empty');
                                    return false;
                                }*/
                                $.getJSON(config.sendAction,'s='+config.s+'&id='+id+'&m='+config.model+'&'+form.serialize(),function(data){
                                        if (data.status == undefined){
                                            $.each(data.result, function(i,val){data.result[i] = val.replace(/\\"/g,'')});
                                            config.onSend($el, data.result);
                                            ovr.find('form').hide();
                                            $('<h2/>',{'css':{'padding':'180px 150px 0','text-align':'center','font-size':'18px','color':'#0459AE'}}).text(data.result).appendTo(ovr);
                                            setTimeout(function(){ovr.find('.close').click()},3000);
                                        }else{
                                            $('<h3/>',{'css':{'margin-top':'100px','text-alight':'center','color':'#777'}}).text(data.status).appendTo(ovr);
                                        }
                                    });
                          }).appendTo(form);
                          if (typeof CKEDITOR != 'undefined'){
                                config.fck = CKEDITOR.replace( 'sendmsg-' + id,{'height':config.textHeight, 'width':config.textWidth, toolbar:'A373SjQQ'} );
                          }
                          config.overlay.load();
                          ovr.find('.close').css({'position':'absolute','top':'5px','right':'5px','cursor':'pointer','height':'35px','width':'35px','background-image':'url(images/manager/close.png)'});
                    })
                })
            }                
        })    
    })(jQuery);
}

/* Common functions */
function uniq(length)
{
    var str = '';
    for(var i=0;i<length;i++)str += String.fromCharCode(parseInt(rand(0x61,0x7a)));
    return str;
}

function rand(min, max) { return Math.random() * (max - min + 1) + min; };
