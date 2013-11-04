define(function(require, exports, module) {

	var $ = require('jquery');
	require('jqueryForm');
	
	//点击Choose File按钮回复原始状态
	$('#attachment').mousedown(function() {
		$('.sucDone, .failDone').hide();
	});
	
	//选择文件后验证上传到temp目录中
	$('#attachment').change(function() {
		if($(this).val() == '') {
			$('#attaInfo').removeClass('suc').addClass('error').html('请选择文件').show();
			$('#uploadForm table').hide();
			return false;
		}
		var options = {
			type: 'POST',
			url: '../../upload.php',
			dataType: 'json',
			success: function(r) {
				if(r.code == 0) {
					$('#attaInfo').removeClass('error').addClass('suc').html(r.msg).show();
					$('input[name="bname"]').val(r.bname);
					$('input[name="bauthor"]').val(r.bauthor);
					$('input[name="bformat"]').val(r.bformat);
					$('input[name="bsize"]').val(r.bsize);
					$('input[name="bpath"]').val(r.bpath);
					$('#uploadForm table').show();
				} else {
					$('#attaInfo').removeClass('suc').addClass('error').html(r.msg).show();
					$('#uploadForm table').hide();
				}
			}
		};
		$('#attaForm').ajaxSubmit(options);
	});
	
	//填写简介后消除空行
	$('textarea[name="bsummary"]').blur(function() {
		var elem = $(this);
		var str = elem.val().replace(/\n\s*/g, '\n');
		str = str.replace(/\n{2,}/g, '\n');		
		elem.val(str);
	});	
	
	//上传和编辑页面表单验证
	var FormCheck = require('mod/formCheck');		
	$('#uploadForm, #editForm').submit(function() {
		if(! FormCheck.checkFileForm()) {
			return false;
		}
	});
});