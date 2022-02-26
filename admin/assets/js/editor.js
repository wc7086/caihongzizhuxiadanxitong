KindEditor.ready(function(K) {
		window.editor = K.create('#editor_id', {
			resizeType : 1,
			allowUpload : false,
			allowPreviewEmoticons : false,
			uploadJson : './ajax.php?act=article_upload',
			items : [
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat','formatblock','hr', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|', 'image', 'link','unlink', 'code', '|','fullscreen','source','preview']
		});
});
