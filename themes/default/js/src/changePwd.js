define(function(require, exports, module) {

	var $ = require('./lib/jquery');
	var Verify = require('./mod/verify');
	var ve = new Verify();	
	
	$('input[name="oldpwd"]').blur(function() {
		ve.verifyOldpwd(true);
	});
	
	$('input[name="pwd"]').blur(function() {
		ve.verifyPwd(true);
	});
	
	$('input[name="repwd"]').blur(function() {
		ve.verifyRepwd(true);
	});
	
	require('./lib/jqueryForm');

	$('#changePwdForm').submit(function() {	
		if(ve.verifyOldpwd() && ve.verifyPwd() && ve.verifyRepwd()) {
			var options = {
				type: 'POST',
				url: '../../user/changePwd.php',
				dataType: 'json',
				beforeSubmit: function() {
					$('#submitTip').html('<img src="../themes/default/images/loading.gif" width="16" height="16" class="loading">');
					$('input[type="submit"]').attr('disabled', true);
				},
				success: function(r) {
					$('#submitTip').html('');
					if(r.code == 0) {
						$('#submitTip').addClass('suc').html('密码修改成功');
					} else if(r.code == 1) {
						$('#oldpwdTip').addClass('error').html('旧密码错误');
					} else {
						$('#submitTip').addClass('error').html('密码修改失败，请重新提交');
					}
					$('input[type="submit"]').attr('disabled', false);
					$(':input').focus(function() {
						$('#submitTip').removeClass('error').removeClass('suc').html('');
					});
				}
			};
			$('#changePwdForm').ajaxSubmit(options);
		}
		return false;
	});	

});