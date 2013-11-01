define(function(require, exports, module) {

	var $ = require('jquery');
	
	//关键字搜索输入框
	var InputVal = require('mod/inputVal');
	var sbkey = new InputVal('input[name="sbkey"]', '请输入关键字');
	sbkey.init();
		
	//表单验证
	var FormCheck = require('mod/formCheck');
	
	$('#filterForm').submit(function() {
		if(! FormCheck.checkFilterForm()) {
			return false;
		}
	});
	
	$('#keyForm').submit(function() {
		if(! FormCheck.checkKeyForm()) {
			return false;
		}
	});
	

});