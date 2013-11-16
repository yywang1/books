define(function(require, exports, module) {

	var $ = require('$');
	var Batch = require('./mod/batch');
	var bu = new Batch();
	
	$('#btnVerifyDir').click(function() {
		bu.verifyDir();
	});

	$('#btnBatchUpload').click(function() {
		bu.doBatchUpload();
	});	
	
});