define(function(require, exports, module) {	

	var $ = require('jquery');
	
	//喜欢
	$('.eva').click(function() {
		var liId = $(this).parents('li').attr('id');
		var bid = parseInt(liId.replace('nov_', ''));
		var File = require('mod/file')
		var File = new File(bid);
		File.doLike();
	});
	
	//删除文件
	$('.btnDelFile').click(function() {
		var liId = $(this).parents('li').attr('id');
		var bid = parseInt(liId.replace('nov_', ''));
		var File = require('mod/file')
		var File = new File(bid);
		File.doDelete();
	});
	
	//下拉框
	require('mod/selectWidget');
	$('.sele').each(function () {
		$(this).selectWidget();
	});
});

