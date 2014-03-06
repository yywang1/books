define(function(require, exports, module) {

	var $ = require('jquery');
	
	//下拉框
	require('./mod/selectWidget');
	$('.sele').each(function () {
		$(this).selectWidget();
	});
	
	//关键字搜索输入框
	var InputVal = require('./mod/inputVal');
	var sbkey = new InputVal('input[name="sbkey"]', '请输入关键字');
	sbkey.init();
		
	//表单验证
	var fc = require('./mod/formCheck');
	
	$('#filterForm').submit(function() {
		if(! fc.checkFilterForm()) {
			return false;
		}
	});
	
	$('#keyForm').submit(function() {
		if(! fc.checkKeyForm()) {
			return false;
		}
	});

	var File = require('./mod/file');
	
	//喜欢
	$('.eva').click(function() {
		var liId = $(this).parents('li').attr('id');
		var bid = parseInt(liId.replace('nov_', ''));
		var f = new File(bid);
		f.doLike();
	});
	
	//删除文件
	$('.btnDelFile').click(function() {
		var liId = $(this).parents('li').attr('id');
		var bid = parseInt(liId.replace('nov_', ''));
		var f = new File(bid);
		f.doDelete();
	});	

});