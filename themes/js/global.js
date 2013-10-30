// JavaScript Document

var loading = '<img src="' + IMG_PATH + 'loading.gif" width="16" height="16" class="loading">';

//输入框
$.fn.inputVal = function (obj) {
	var str = obj.str ? obj.str : ''; //请输入XX
	var cla = 'ini'; //请输入XX时的class
	if ($(this).val() == str) {
		$(this).addClass(cla);
	}
	$(this).focus(function () {
		$(this).removeClass(cla);
		if ($(this).val() == str) {
			$(this).val('');
		}
	});
	$(this).blur(function () {
		if ($(this).val() == '') {
			$(this).addClass(cla).val(str);
		}
	});
};

/*tab页*/
$.fn.funcTab = function (obj) {

	var $o = $(this);
	var $tags = $(this).children('.tabTit').children('li');
	var $conts = $(this).children('.tabCont').children('.tabCont_c');

	var init = 0; //默认展开的tab页索引
	$tags.each(function (i) {
		if ($(this).hasClass('cur')) {
			init = i;
		}
	});
	$tags.removeClass('cur').eq(init).addClass('cur');
	$conts.hide().eq(init).show();

	$tags.click(function () {
		var i = $(this).index();
		$tags.removeClass('cur').eq(i).addClass('cur');
		$conts.hide().eq(i).show();
	});

	$tags.mouseenter(function () {
		$(this).addClass('hov');
	}).mouseleave(function () {
		$(this).removeClass('hov');
	});

};

//模拟下拉框
$.fn.selectWidget = function (obj) {
	var $sele = $(this);
	var $text = $(this).children('span');
	var $hidden = $(this).children('input[type="hidden"]');
	var $menu = $(this).children('ul');
	if ($menu.children('li').length > 10) {
		$menu.height(250);
	}

	$text.mousedown(function () {
		showMenu();
	});
	
	$menu.find('li').click(function () {
		if ($(this).children('a').length == 0) {
			$text.html($(this).html());
			$hidden.val($(this).index());
		}
		$menu.hide();
		$sele.css('z-index', '0');
		$(document).unbind('click', hideMenu);
	});

	var showMenu = function () {
		$('.sele').css('z-index', '0').find('ul').hide();
		$sele.css('z-index', '1');
		$menu.show();
		$(document).bind('click', hideMenu);
	};

	var hideMenu = function (e) {
		var src = e ? e.target : e.srcElement;
		do {
			if ($(src).closest('.sele').length > 0)
				return;
			src = src.parentNode;
		} while (src.parentNode)

		$menu.hide();
		$sele.css('z-index', '0');
		$(document).unbind('click', hideMenu);
	};
};

//初始化下拉框
$.fn.selectInit = function (obj) {
	var $sele = $(this);
	var $text = $(this).children('span');
	var $hidden = $(this).children('input[type="hidden"]');
	var initVal = (obj && obj.val) ? obj.val : '0';
	var initStr = (obj && obj.str) ? obj.str : '-';
	$hidden.val(initVal);
	$text.html(initStr);
};


function doLike(bid) {
	var backUrl = encodeURI(location.href);
	var eli = $('#nov_' + bid);
	$.ajax({
		type: 'POST',
		url: WEB_ROOT + 'ajax.php',
		dataType: 'json',
		data: {'act':'doLike','bid':bid},
		success: function(r){
			if(r.code == 0) {
				var evaCount = parseInt(eli.find('.evaCount').html());
				if(r.iseva == 1) {
					evaCount ++;
					eli.find('.eva').addClass('eva_1');
				} else {
					evaCount --;
					eli.find('.eva').removeClass('eva_1');
				}
				eli.find('.evaCount').html(evaCount);
			} else if(r.code == 1) {
				location.href = WEB_ROOT + 'login.php?back=' + backUrl;
			} else {
				alert('操作失败！');
			}
		}
	});
}

function deleteFile(bid) {
	$.ajax({
		type: 'POST',
		url: WEB_ROOT + 'ajax.php',
		dataType: 'text',
		data: {'act':'deleteFile','bid':bid},
		success: function(r){
			if(r == '1') {
				//var evaClasses = $('#nov_' + bid).find('.eva').attr('class').split(' ');
				//$('#nov_' + bid).find('.eva').removeClass(evaClasses[1]).addClass('eva_99').attr('title','已删除');
				//$('#nov_' + bid).find('.oc2').fadeOut('fast');
				$('#nov_' + bid).hide('fast');
			} else {
				alert('操作失败！');
			}
		}
	});
}

function removeSpace(elem) {
	var elem = $(elem);
	var str = elem.val().replace(/\n\s*/g, '\n');
	str = str.replace(/\n{2,}/g, '\n');
	
	elem.val(str);
}

//upload and edit file
function checkFileForm() {
	var bname = $('input[name="bname"]');
	var bauthor = $('input[name="bauthor"]');
	var btype = $('input[name="btype"]');
	var btags = $('input[name="btags[]"]:checked');
	if(! bname.val()) {
		$('#bnameTip').html('请填写书名').addClass('error').show();
		bname.focus(function() {
			$('#bnameTip').html('').removeClass('error').hide();
		})
		return false;
	}
	if(! bauthor.val()) {
		$('#bauthorTip').html('请填写作者').addClass('error').show();
		bauthor.focus(function() {
			$('#bauthorTip').html('').removeClass('error').hide();
		});
		return false;
	}
	if(btype.val() == 0) {
		$('#btypeTip').html('请选择分类').addClass('error').show();
		btype.next().mousedown(function() {
			$('#btypeTip').html('').removeClass('error').hide();
		});
		return false;
	}
	if(btags.length > 5) {
		$('#btagsTip').html('标签不能超过5个').addClass('error').show();
		btags.click(function() {
			$('#btagsTip').html('').removeClass('error').hide();
		});
		return false;
	}
	
	return true;
}

$(document).ready(function () {

	//------------------------------ tab页 ------------------------------
	$('.tab').each(function () {
		$(this).funcTab();
	});

	//------------------------------ 下拉框 ------------------------------
	$('.sele').each(function () {
		$(this).selectWidget();
	});

})
