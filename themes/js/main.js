define(function(require, exports, module) {	

	var $ = require('jquery');
	
	//喜欢
	$('.eva').click(function() {
		var liId = $(this).parents('li').attr('id');
		var bid = parseInt(liId.replace('nov_', ''));
		require.async(['mod/file'], function(File) {
			var f = new File(bid);
			f.doLike();
		});
		
	});
	
	//删除文件
	$('.btnDelFile').click(function() {
		var liId = $(this).parents('li').attr('id');
		var bid = parseInt(liId.replace('nov_', ''));
		require.async(['mod/file'], function(File) {
			var f = new File(bid);
			f.doDelete();
		});
	});
	
	//下拉框
	if($('.sele').length > 0) {
		require.async(['mod/selectWidget'], function() {
			$('.sele').each(function () {
				$(this).selectWidget();
			});
		});
	}
		
});

