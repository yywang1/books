define(function(require, exports, module) {

	var $ = require('jquery');
	var Verify = require('mod/verify');
	var ve = new Verify();	
	
	$('input[name="email"]').blur(function() {
		ve.verifyEmailPwd();
	});
	
	$('input[name="pwd"]').blur(function() {
		ve.verifyPwd(true);
	});
	
	$('input[name="repwd"]').blur(function() {
		ve.verifyRepwd(true);
	});
	
	require('jqueryForm');
	var gp = require('mod/getParam');
	var backUrl = gp.getParamVal('back');

	$('#findPwdForm').submit(function() {
		if(ve.verifyEmailPwd() && ve.verifyPwd() && ve.verifyRepwd()) {
			var options = {
				type: 'POST',
				url: '../../login.php',
				dataType: 'text',
				beforeSubmit: function() {
					$('#submitTip').html('<img src="themes/images/loading.gif" width="16" height="16" class="loading">');
					$('input[type="submit"]').attr('disabled', true);
				},
				success: function(r) {
					if(r) {
						$('#submitTip').addClass('suc').html('密码设置成功');
						location.href = '../../login.php?back=' + backUrl;
					} else {
						$('#submitTip').addClass('error').html('设置失败，请重新提交');
						$('input[type="submit"]').attr('disabled', false);
						$(':input').focus(function() {
							$('#submitTip').removeClass('error').removeClass('suc').html('');
						});
					}
				}
			};
			$('#findPwdForm').ajaxSubmit(options);
		}	
		return false;
	});	

});