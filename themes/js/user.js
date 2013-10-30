// JavaScript Document

function verifyName(isAjax) {
	var isAjax = arguments[0] ? arguments[0] : false;
	var name = $('input[name="name"]');
	var nameVal = $('input[name="name"]').val();
	
	name.focus(function () {
		$('#nameTip').removeClass('error').removeClass('suc').html('由3-10位英文字母或数字组成');
	});
	
	if ((nameVal.length < 3) || (nameVal.length > 10) || (nameVal.search(/^[A-Za-z0-9]+$/g))) {
		$('#nameTip').addClass('error');
		return false;
	}

	if (isAjax) {
		$.ajax({
			type : 'POST',
			url : WEB_ROOT + 'login.php',
			dataType : 'text',
			data : {
				act : 'verifyName',
				name : nameVal
			},
			beforeSend : function () {
				$('#nameTip').html(loading);
			},
			success : function (r) {
				if (r) {
					$('#nameTip').addClass('error').html('该用户名已被占用');
				} else {
					$('#nameTip').addClass('suc').html('可用');
				}
			}
		});
	}
	return true;
}

function verifyEmail(isAjax) {
	var isAjax = arguments[0] ? arguments[0] : false;
	var email = $('input[name="email"]');
	var emailVal = $('input[name="email"]').val();
	var origTip = $('#emailTip').html();
	email.focus(function () {
		$('#emailTip').removeClass('error').removeClass('suc').html(origTip);
	});
	
	if (emailVal.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/g)) {
		$('#emailTip').addClass('error').html('邮箱格式错误');
		return false;
	}
	if (isAjax) {
		$.ajax({
			type : 'POST',
			url : WEB_ROOT + 'login.php',
			dataType : 'text',
			data : {
				act : 'verifyEmail',
				email : emailVal
			},
			beforeSend : function () {
				$('#emailTip').html(loading);
			},
			success : function (r) {
				if (r) {
					$('#emailTip').addClass('error').html('该邮箱已被占用');
				} else {
					$('#emailTip').addClass('suc').html('可用');
				}
			}
		});
	}
	return true;
}

function verifyPwd(isShowRight) {
	var isShowRight = arguments[0] ? arguments[0] : false;
	var pwd = $('input[name="pwd"]');
	var pwdVal = pwd.val();

	pwd.focus(function () {
		$('#pwdTip').removeClass('error').removeClass('suc').html('由6-16位下划线、英文字母或数字组成');
	});

	if ((pwdVal.length < 6) || (pwdVal.length > 16) || (pwdVal.search(/^\w+$/g))) {
		$('#pwdTip').addClass('error');
		return false;
	}
	if(isShowRight) {
		$('#pwdTip').addClass('suc').html('可用');
	}
	return true;
}

function verifyRepwd(isShowRight) {
	var repwd = $('input[name="repwd"]');
	var repwdVal = $('input[name="repwd"]').val();
	
	repwd.focus(function () {
		$('#repwdTip').removeClass('error').removeClass('suc').html('');
	});	
	
	if (! repwdVal) {
		$('#repwdTip').addClass('error').html('密码不能为空');
		return false;
	}
	
	if (repwdVal != $('input[name="pwd"]').val()) {
		$('#repwdTip').addClass('error').html('密码不一致');
		return false;
	}
	if(isShowRight) {
		$('#repwdTip').addClass('suc').html('可用');
	}
	return true;
}
