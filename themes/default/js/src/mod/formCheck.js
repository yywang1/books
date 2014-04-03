//表单验证
define(function(require, exports, module) {

	var $ = require('jquery');
	
	module.exports.checkKeyForm = function() {
		var sbkey = $('input[name="sbkey"]').val();
		if(sbkey == '请输入关键字' || sbkey == ' ') {
			alert('请输入关键字');
			return false;
		}
		return true;
	};
	
	module.exports.checkFilterForm = function() {
		var isTrue = false;
		$('#filterForm .sele input[type="hidden"]').each(function(index, element) {
			if($(element).val() != 0) {
				isTrue = true;
			}
		});
		if(isTrue) {
			return true;
		} else {
			alert('请选择筛选条件');
			return false;
		}		
	};
	
});
