define(function(require, exports, module) {
	
	var $ = require('jquery');
	
	module.exports.removeSpace = function(elem) {
		var elem = $(elem);
		var str = elem.val().replace(/\n\s*/g, '\n');
		str = str.replace(/\n{2,}/g, '\n');		
		elem.val(str);
	};

});

