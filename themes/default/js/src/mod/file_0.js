define(function(require, exports, module) {

	var $ = require('jquery');
	
	function File(bid) {
		 this.bid = parseInt(bid);
		 this.container = $('#nov_' + bid);
		 this.evaWrap = $('#evaWrap_' + bid);
		 this.backUrl = encodeURI(location.href);
	}
	module.exports = File;
	
	//喜欢或取消喜欢
	File.prototype.doLike = function() {
		var nov = this;
		var bid = nov.bid;
		var evaWrap = nov.evaWrap;
		$.ajax({
			type: 'POST',
			url: '../../../ajax.php',
			dataType: 'json',
			data: {'act':'doLike','bid':bid},
			success: function(r){
				if(r.code == 0) {
					var evaCount = parseInt(evaWrap.find('.evaCount').html());
					if(r.iseva == 1) {
						evaCount ++;
						evaWrap.find('.eva').addClass('eva_1');
					} else {
						evaCount --;
						evaWrap.find('.eva').removeClass('eva_1');
					}
					evaWrap.find('.evaCount').html(evaCount);
				} else if(r.code == 1) {
					location.href = '../../login.php?back=' + nov.backUrl;
				} else {
					alert('操作失败！');
				}
			}
		});
	}
	
	//删除文件
	File.prototype.doDelete = function() {
		var bid = this.bid;
		var container = this.container;
		$.ajax({
			type: 'POST',
			url: '../../../ajax.php',
			dataType: 'text',
			data: {'act':'deleteFile','bid':bid},
			success: function(r){
				if(r == '1') {
					container.hide('fast');
				} else {
					alert('操作失败！');
				}
			}
		});
	}
	
});
