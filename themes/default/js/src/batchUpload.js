define(function(require, exports, module) {

	var $ = require('jquery');
	var Batch = require('./mod/batch');
	var bu = new Batch();
	
	$('#btnVerifyDir').click(function() {
		bu.verifyDir();
	});

	$('#buForm').submit(function() {
		bu.doBatchUpload();
		return false;
	});	
	
});