define(function(require, exports, module) {	

	var $ = require('jquery');
	var File = require('./mod/file');
	
	//喜欢
	$('.eva').click(function() {
		var evaWrapId = $(this).parents('.heat').attr('id');
		var bid = parseInt(evaWrapId.replace('evaWrap_', ''));
		var f = new File(bid);
		f.doLike();
	});
	
});

