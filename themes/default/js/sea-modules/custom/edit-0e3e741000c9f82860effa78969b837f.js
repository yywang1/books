define("custom/edit",["jquery/jquery/1.10.1/jquery","./mod/selectWidget","./mod/formCheck"],function(e){var t=e("jquery/jquery/1.10.1/jquery");e("./mod/selectWidget"),t(".sele").each(function(){t(this).selectWidget()}),t('textarea[name="bsummary"]').blur(function(){var e=t(this),r=e.val().replace(/\n\s*/g,"\n");r=r.replace(/\n{2,}/g,"\n"),e.val(r)});var r=e("./mod/formCheck");t("#editForm").submit(function(){return r.checkFileForm()?void 0:!1})}),define("custom/mod/selectWidget",["jquery/jquery/1.10.1/jquery"],function(e){var t=e("jquery/jquery/1.10.1/jquery");t.fn.selectWidget=function(){var e=t(this),r=t(this).children("span"),a=t(this).children('input[type="hidden"]'),n=t(this).children("ul");n.children("li").length>10&&n.height(250),r.mousedown(function(){i()}),n.find("li").click(function(){0==t(this).children("a").length&&(r.html(t(this).html()),a.val(t(this).index())),n.hide(),e.css("z-index","0"),t(document).unbind("click",o)});var i=function(){t(".sele").css("z-index","0").find("ul").hide(),e.css("z-index","1"),n.show(),t(document).bind("click",o)},o=function(r){var a=r?r.target:r.srcElement;do{if(t(a).closest(".sele").length>0)return;a=a.parentNode}while(a.parentNode);n.hide(),e.css("z-index","0"),t(document).unbind("click",o)}},t.fn.selectInit=function(e){t(this);var r=t(this).children("span"),a=t(this).children('input[type="hidden"]'),n=e&&e.val?e.val:"0",i=e&&e.str?e.str:"-";a.val(n),r.html(i)}}),define("custom/mod/formCheck",["jquery/jquery/1.10.1/jquery"],function(e,t,r){var a=e("jquery/jquery/1.10.1/jquery");r.exports.checkKeyForm=function(){var e=a('input[name="sbkey"]').val();return"请输入关键字"==e||" "==e?(alert("请输入关键字"),!1):!0},r.exports.checkFilterForm=function(){var e=!1;return a('#filterForm .sele input[type="hidden"]').each(function(t,r){0!=a(r).val()&&(e=!0)}),e?!0:(alert("请选择筛选条件"),!1)},r.exports.checkFileForm=function(){var e=a('input[name="bname"]'),t=a('input[name="bauthor"]'),r=a('input[name="btype"]'),n=a('input[name="btags[]"]:checked');return e.val()?t.val()?0==r.val()?(a("#btypeTip").html("请选择分类").addClass("error").show(),r.next().mousedown(function(){a("#btypeTip").html("").removeClass("error").hide()}),!1):n.length>5?(a("#btagsTip").html("标签不能超过5个").addClass("error").show(),n.click(function(){a("#btagsTip").html("").removeClass("error").hide()}),!1):!0:(a("#bauthorTip").html("请填写作者").addClass("error").show(),t.focus(function(){a("#bauthorTip").html("").removeClass("error").hide()}),!1):(a("#bnameTip").html("请填写书名").addClass("error").show(),e.focus(function(){a("#bnameTip").html("").removeClass("error").hide()}),!1)}});
