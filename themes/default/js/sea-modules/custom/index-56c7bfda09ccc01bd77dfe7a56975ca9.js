define("custom/index",["jquery/jquery/1.10.1/jquery","./mod/file"],function(e){var t=e("jquery/jquery/1.10.1/jquery"),a=e("./mod/file");t(".eva").click(function(){var e=t(this).parents("li").attr("id"),i=parseInt(e.replace("nov_","")),n=new a(i);n.doLike()}),t(".btnDelFile").click(function(){var e=t(this).parents("li").attr("id"),i=parseInt(e.replace("nov_","")),n=new a(i);n.doLike()})}),define("custom/mod/file",["jquery/jquery/1.10.1/jquery"],function(e,t,a){function i(e){this.bid=parseInt(e),this.container=n("#nov_"+e),this.backUrl=encodeURI(location.href)}var n=e("jquery/jquery/1.10.1/jquery");a.exports=i,i.prototype.doLike=function(){var e=this,t=e.bid,a=e.container;n.ajax({type:"POST",url:"../../../ajax.php",dataType:"json",data:{act:"doLike",bid:t},success:function(t){if(0==t.code){var i=parseInt(a.find(".evaCount").html());1==t.iseva?(i++,a.find(".eva").addClass("eva_1")):(i--,a.find(".eva").removeClass("eva_1")),a.find(".evaCount").html(i)}else 1==t.code?location.href="../../login.php?back="+e.backUrl:alert("操作失败！")}})},i.prototype.doDelete=function(){var e=this.bid,t=this.container;n.ajax({type:"POST",url:"../../../ajax.php",dataType:"text",data:{act:"deleteFile",bid:e},success:function(e){"1"==e?t.hide("fast"):alert("操作失败！")}})}});