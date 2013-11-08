define("custom/forgetPwd",["jquery/jquery/1.10.1/jquery","./mod/verify","./lib/jqueryForm","./mod/getParam"],function(e){var t=e("jquery/jquery/1.10.1/jquery"),r=e("./mod/verify"),a=new r;t('input[name="email"]').blur(function(){a.verifyEmailPwd()}),t('input[name="pwd"]').blur(function(){a.verifyPwd(!0)}),t('input[name="repwd"]').blur(function(){a.verifyRepwd(!0)}),e("./lib/jqueryForm");var n=e("./mod/getParam"),i=n.getParamVal("back");t("#findPwdForm").submit(function(){if(a.verifyEmailPwd()&&a.verifyPwd()&&a.verifyRepwd()){var e={type:"POST",url:"../../login.php",dataType:"text",beforeSubmit:function(){t("#submitTip").html('<img src="themes/images/loading.gif" width="16" height="16" class="loading">'),t('input[type="submit"]').attr("disabled",!0)},success:function(e){e?(t("#submitTip").addClass("suc").html("密码设置成功"),location.href="../../login.php?back="+i):(t("#submitTip").addClass("error").html("设置失败，请重新提交"),t('input[type="submit"]').attr("disabled",!1),t(":input").focus(function(){t("#submitTip").removeClass("error").removeClass("suc").html("")}))}};t("#findPwdForm").ajaxSubmit(e)}return!1})}),define("custom/mod/verify",["jquery/jquery/1.10.1/jquery","custom/lib/jqueryForm"],function(e,t,r){function a(){this.name=n('input[name="name"]'),this.nameTip=n("#nameTip"),this.email=n('input[name="email"]'),this.emailTip=n("#emailTip"),this.pwd=n('input[name="pwd"]'),this.pwdTip=n("#pwdTip"),this.repwd=n('input[name="repwd"]'),this.repwdTip=n("#repwdTip")}var n=e("jquery/jquery/1.10.1/jquery");e("custom/lib/jqueryForm"),r.exports=a,a.prototype.verifyName=function(e){var e=arguments[0]?arguments[0]:!1,t=this.name,r=t.val(),a=this.nameTip;return t.focus(function(){a.removeClass("error").removeClass("suc").html("由3-10位英文字母或数字组成")}),3>r.length||r.length>10||r.search(/^[A-Za-z0-9]+$/g)?(a.addClass("error"),!1):(e&&n.ajax({type:"POST",url:"../../../login.php",dataType:"text",data:{act:"verifyName",name:r},beforeSend:function(){a.html('<img src="themes/images/loading.gif" width="16" height="16" class="loading">')},success:function(e){e?a.addClass("error").html("该用户名已被占用"):a.addClass("suc").html("可用")}}),!0)},a.prototype.verifyEmail=function(e){var e=arguments[0]?arguments[0]:!1,t=this.email,r=t.val(),a=this.emailTip,i=a.html();return t.focus(function(){a.removeClass("error").removeClass("suc").html(i)}),r.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/g)?(a.addClass("error").html("邮箱格式错误"),!1):(e&&n.ajax({type:"POST",url:"../../../login.php",dataType:"text",data:{act:"verifyEmail",email:r},beforeSend:function(){a.html('<img src="themes/images/loading.gif" width="16" height="16" class="loading">')},success:function(e){e?a.addClass("error").html("该邮箱已被占用"):a.addClass("suc").html("可用")}}),!0)},a.prototype.verifyPwd=function(e){var e=arguments[0]?arguments[0]:!1,t=this.pwd,r=t.val(),a=this.pwdTip;return t.focus(function(){a.removeClass("error").removeClass("suc").html("由6-16位下划线、英文字母或数字组成")}),6>r.length||r.length>16||r.search(/^\w+$/g)?(a.addClass("error"),!1):(e&&a.addClass("suc").html("可用"),!0)},a.prototype.verifyRepwd=function(e){var t=this.repwd,r=t.val(),a=this.repwdTip;return t.focus(function(){a.removeClass("error").removeClass("suc").html("")}),r?r!=n('input[name="pwd"]').val()?(a.addClass("error").html("密码不一致"),!1):(e&&a.addClass("suc").html("可用"),!0):(a.addClass("error").html("密码不能为空"),!1)},a.prototype.verifyNameReg=function(){var e=this.name.val(),t=this.nameTip,r=n('input[name="vname"]').val();return e==r&&""!=r?(t.addClass("suc").html("可用"),!0):(this.verifyName(!0)&&n('input[name="vname"]').val(e),void 0)},a.prototype.verifyEmailReg=function(){var e=this.email.val(),t=this.emailTip,r=n('input[name="vemail"]').val();return e==r&&""!=r?(t.addClass("suc").html("可用"),!0):(this.verifyEmail(!0)&&n('input[name="vemail"]').val(e),void 0)},a.prototype.verifyEmailPwd=function(){var e=this.email.val(),t=n('input[name="vemail"]').val();return e==t&&""!=t?(n("#emailTip").addClass("suc").html("可用"),!0):(this.verifyEmail()&&n.ajax({type:"POST",url:"../../../login.php",dataType:"text",data:{act:"verifyEmail",email:e},beforeSend:function(){n("#emailTip").html('<img src="themes/images/loading.gif" width="16" height="16" class="loading">')},success:function(t){t?(n("#emailTip").addClass("suc").html("正确"),n(".pwdtr").show(),n('input[name="act"]').val("findPwd"),n('input[name="vemail"]').val(e)):n("#emailTip").addClass("error").html("该邮箱未在网站注册过")}}),void 0)}}),define("custom/lib/jqueryForm",["jquery/jquery/1.10.1/jquery"],function(e){e("jquery/jquery/1.10.1/jquery"),function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(t.target).ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=this;if(i.clk=r,"image"==r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n={};n.fileapi=void 0!==e("<input type='file'/>").get(0).files,n.formdata=void 0!==window.FormData;var i=!!e.fn.prop;e.fn.attr2=function(){if(!i)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t){function r(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;o>a;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function o(a){for(var n=new FormData,i=0;a.length>i;i++)n.append(a[i].name,a[i].value);if(t.extraData){var o=r(t.extraData);for(i=0;o.length>i;i++)o[i]&&n.append(o[i][0],o[i][1])}t.data=null;var s=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:u||"POST"});t.uploadProgress&&(s.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(100*(a/n))),t.uploadProgress(e,a,n,r)},!1),r}),s.data=null;var l=s.beforeSend;return s.beforeSend=function(e,r){r.data=t.formData?t.formData:n,l&&l.call(this,e,r)},e.ajax(s)}function s(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(r){a("cannot get iframe.contentWindow document: "+r)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function o(){function t(){try{var e=n(g).readyState;a("state = "+e),e&&"uninitialized"==e.toLowerCase()&&setTimeout(t,50)}catch(r){a("Server abort: ",r," (",r.name,")"),s(C),T&&clearTimeout(T),T=void 0}}var r=m.attr2("target"),i=m.attr2("action");w.setAttribute("target",p),(!u||/post/i.test(u))&&w.setAttribute("method","POST"),i!=d.url&&w.setAttribute("action",d.url),d.skipEncodingOverride||u&&!/post/i.test(u)||m.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),d.timeout&&(T=setTimeout(function(){j=!0,s(k)},d.timeout));var o=[];try{if(d.extraData)for(var l in d.extraData)d.extraData.hasOwnProperty(l)&&(e.isPlainObject(d.extraData[l])&&d.extraData[l].hasOwnProperty("name")&&d.extraData[l].hasOwnProperty("value")?o.push(e('<input type="hidden" name="'+d.extraData[l].name+'">').val(d.extraData[l].value).appendTo(w)[0]):o.push(e('<input type="hidden" name="'+l+'">').val(d.extraData[l]).appendTo(w)[0]));d.iframeTarget||v.appendTo("body"),g.attachEvent?g.attachEvent("onload",s):g.addEventListener("load",s,!1),setTimeout(t,15);try{w.submit()}catch(c){var f=document.createElement("form").submit;f.apply(w)}}finally{w.setAttribute("action",i),r?w.setAttribute("target",r):m.removeAttr("target"),e(o).remove()}}function s(t){if(!y.aborted&&!E){if(L=n(g),L||(a("cannot access response document"),t=C),t===k&&y)return y.abort("timeout"),S.reject(y,"timeout"),void 0;if(t==C&&y)return y.abort("server abort"),S.reject(y,"error","server abort"),void 0;if(L&&L.location.href!=d.iframeSrc||j){g.detachEvent?g.detachEvent("onload",s):g.removeEventListener("load",s,!1);var r,i="success";try{if(j)throw"timeout";var o="xml"==d.dataType||L.XMLDocument||e.isXMLDoc(L);if(a("isXml="+o),!o&&window.opera&&(null===L.body||!L.body.innerHTML)&&--A)return a("requeing onLoad callback, DOM not available"),setTimeout(s,250),void 0;var u=L.body?L.body:L.documentElement;y.responseText=u?u.innerHTML:null,y.responseXML=L.XMLDocument?L.XMLDocument:L,o&&(d.dataType="xml"),y.getResponseHeader=function(e){var t={"content-type":d.dataType};return t[e.toLowerCase()]},u&&(y.status=Number(u.getAttribute("status"))||y.status,y.statusText=u.getAttribute("statusText")||y.statusText);var l=(d.dataType||"").toLowerCase(),c=/(json|script|text)/.test(l);if(c||d.textarea){var m=L.getElementsByTagName("textarea")[0];if(m)y.responseText=m.value,y.status=Number(m.getAttribute("status"))||y.status,y.statusText=m.getAttribute("statusText")||y.statusText;else if(c){var p=L.getElementsByTagName("pre")[0],h=L.getElementsByTagName("body")[0];p?y.responseText=p.textContent?p.textContent:p.innerText:h&&(y.responseText=h.textContent?h.textContent:h.innerText)}}else"xml"==l&&!y.responseXML&&y.responseText&&(y.responseXML=O(y.responseText));try{F=P(y,l,d)}catch(b){i="parsererror",y.error=r=b||i}}catch(b){a("error caught: ",b),i="error",y.error=r=b||i}y.aborted&&(a("upload aborted"),i=null),y.status&&(i=y.status>=200&&300>y.status||304===y.status?"success":"error"),"success"===i?(d.success&&d.success.call(d.context,F,"success",y),S.resolve(y.responseText,"success",y),f&&e.event.trigger("ajaxSuccess",[y,d])):i&&(void 0===r&&(r=y.statusText),d.error&&d.error.call(d.context,y,i,r),S.reject(y,"error",r),f&&e.event.trigger("ajaxError",[y,d,r])),f&&e.event.trigger("ajaxComplete",[y,d]),f&&!--e.active&&e.event.trigger("ajaxStop"),d.complete&&d.complete.call(d.context,y,i),E=!0,d.timeout&&clearTimeout(T),setTimeout(function(){d.iframeTarget?v.attr("src",d.iframeSrc):v.remove(),y.responseXML=null},100)}}}var l,c,d,f,p,v,g,y,b,x,j,T,w=m[0],S=e.Deferred();if(S.abort=function(e){y.abort(e)},r)for(c=0;h.length>c;c++)l=e(h[c]),i?l.prop("disabled",!1):l.removeAttr("disabled");if(d=e.extend(!0,{},e.ajaxSettings,t),d.context=d.context||d,p="jqFormIO"+(new Date).getTime(),d.iframeTarget?(v=e(d.iframeTarget),x=v.attr2("name"),x?p=x:v.attr2("name",p)):(v=e('<iframe name="'+p+'" src="'+d.iframeSrc+'" />'),v.css({position:"absolute",top:"-1000px",left:"-1000px"})),g=v[0],y={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{g.contentWindow.document.execCommand&&g.contentWindow.document.execCommand("Stop")}catch(n){}v.attr("src",d.iframeSrc),y.error=r,d.error&&d.error.call(d.context,y,r,t),f&&e.event.trigger("ajaxError",[y,d,r]),d.complete&&d.complete.call(d.context,y,r)}},f=d.global,f&&0===e.active++&&e.event.trigger("ajaxStart"),f&&e.event.trigger("ajaxSend",[y,d]),d.beforeSend&&d.beforeSend.call(d.context,y,d)===!1)return d.global&&e.active--,S.reject(),S;if(y.aborted)return S.reject(),S;b=w.clk,b&&(x=b.name,x&&!b.disabled&&(d.extraData=d.extraData||{},d.extraData[x]=b.value,"image"==b.type&&(d.extraData[x+".x"]=w.clk_x,d.extraData[x+".y"]=w.clk_y)));var k=1,C=2,D=e("meta[name=csrf-token]").attr("content"),q=e("meta[name=csrf-param]").attr("content");q&&D&&(d.extraData=d.extraData||{},d.extraData[q]=D),d.forceSync?o():setTimeout(o,10);var F,L,E,A=50,O=e.parseXML||function(e,t){return window.ActiveXObject?(t=new ActiveXObject("Microsoft.XMLDOM"),t.async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!=t.documentElement.nodeName?t:null},M=e.parseJSON||function(e){return window.eval("("+e+")")},P=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i="xml"===r||!r&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&("json"===r||!r&&n.indexOf("json")>=0?o=M(o):("script"===r||!r&&n.indexOf("javascript")>=0)&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var u,l,c,m=this;"function"==typeof t?t={success:t}:void 0===t&&(t={}),u=t.type||this.attr2("method"),l=t.url||this.attr2("action"),c="string"==typeof l?e.trim(l):"",c=c||window.location.href||"",c&&(c=(c.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:c,success:e.ajaxSettings.success,type:u||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var d={};if(this.trigger("form-pre-serialize",[this,t,d]),d.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&t.beforeSerialize(this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var f=t.traditional;void 0===f&&(f=e.ajaxSettings.traditional);var p,h=[],v=this.formToArray(t.semantic,h);if(t.data&&(t.extraData=t.data,p=e.param(t.data,f)),t.beforeSubmit&&t.beforeSubmit(v,this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[v,this,t,d]),d.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var g=e.param(v,f);p&&(g=g?g+"&"+p:p),"GET"==t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+g,t.data=null):t.data=g;var y=[];if(t.resetForm&&y.push(function(){m.resetForm()}),t.clearForm&&y.push(function(){m.clearForm(t.includeHidden)}),!t.dataType&&t.target){var b=t.success||function(){};y.push(function(r){var a=t.replaceTarget?"replaceWith":"html";e(t.target)[a](r).each(b,arguments)})}else t.success&&y.push(t.success);if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=y.length;o>i;i++)y[i].apply(n,[e,r,a||m,m])},t.error){var x=t.error;t.error=function(e,r,a){var n=t.context||this;x.apply(n,[e,r,a,m])}}if(t.complete){var j=t.complete;t.complete=function(e,r){var a=t.context||this;j.apply(a,[e,r,m])}}var T=e("input[type=file]:enabled",this).filter(function(){return""!==e(this).val()}),w=T.length>0,S="multipart/form-data",k=m.attr("enctype")==S||m.attr("encoding")==S,C=n.fileapi&&n.formdata;a("fileAPI :"+C);var D,q=(w||k)&&!C;t.iframe!==!1&&(t.iframe||q)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){D=s(v)}):D=s(v):D=(w||k)&&C?o(v):e.ajax(t),m.removeData("jqxhr").data("jqxhr",D);for(var F=0;h.length>F;F++)h[F]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n){if(n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var i={s:this.selector,c:this.context};return!e.isReady&&i.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(i.s,i.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().bind("submit.form-plugin",n,t).bind("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r){var a=[];if(0===this.length)return a;var i=this[0],o=t?i.getElementsByTagName("*"):i.elements;if(!o)return a;var s,u,l,c,m,d,f;for(s=0,d=o.length;d>s;s++)if(m=o[s],l=m.name,l&&!m.disabled)if(t&&i.clk&&"image"==m.type)i.clk==m&&(a.push({name:l,value:e(m).val(),type:m.type}),a.push({name:l+".x",value:i.clk_x},{name:l+".y",value:i.clk_y}));else if(c=e.fieldValue(m,!0),c&&c.constructor==Array)for(r&&r.push(m),u=0,f=c.length;f>u;u++)a.push({name:l,value:c[u]});else if(n.fileapi&&"file"==m.type){r&&r.push(m);var p=m.files;if(p.length)for(u=0;p.length>u;u++)a.push({name:l,value:p[u],type:m.type});else a.push({name:l,value:"",type:m.type})}else null!==c&&c!==void 0&&(r&&r.push(m),a.push({name:l,value:c,type:m.type,required:m.required}));if(!t&&i.clk){var h=e(i.clk),v=h[0];l=v.name,l&&!v.disabled&&"image"==v.type&&(a.push({name:l,value:h.val()}),a.push({name:l+".x",value:i.clk_x},{name:l+".y",value:i.clk_y}))}return a},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor==Array)for(var i=0,o=n.length;o>i;i++)r.push({name:a,value:n[i]});else null!==n&&n!==void 0&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;n>a;a++){var i=this[a],o=e.fieldValue(i,t);null===o||void 0===o||o.constructor==Array&&!o.length||(o.constructor==Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,n=t.type,i=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"==n||"button"==n||("checkbox"==n||"radio"==n)&&!t.checked||("submit"==n||"image"==n)&&t.form&&t.form.clk!=t||"select"==i&&-1==t.selectedIndex))return null;if("select"==i){var o=t.selectedIndex;if(0>o)return null;for(var s=[],u=t.options,l="select-one"==n,c=l?o+1:u.length,m=l?o:0;c>m;m++){var d=u[m];if(d.selected){var f=d.value;if(f||(f=d.attributes&&d.attributes.value&&!d.attributes.value.specified?d.text:d.value),l)return f;s.push(f)}}return s}return e(t).val()},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"==n?this.value="":"checkbox"==a||"radio"==a?this.checked=!1:"select"==n?this.selectedIndex=-1:"file"==a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(t===!0&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"==r||"radio"==r)this.checked=t;else if("option"==this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"==a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1}("undefined"!=typeof jQuery?jQuery:window.Zepto)}),define("custom/mod/getParam",[],function(e,t,r){r.exports.getParamVal=function(e){if(paramVal="",isFound=!1,0==window.location.search.indexOf("?")&&window.location.search.indexOf("=")>1)for(arrSource=unescape(window.location.search).substring(1,window.location.search.length).split("&"),i=0;arrSource.length>i&&!isFound;)arrSource[i].indexOf("=")>0&&arrSource[i].split("=")[0].toLowerCase()==e.toLowerCase()&&(paramVal=arrSource[i].split("=")[1],isFound=!0),i++;return paramVal}});
