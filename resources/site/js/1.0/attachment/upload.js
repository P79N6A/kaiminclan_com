
$(function(){
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'click_upload_cover', // you can pass an id...
		container: document.getElementById('click_upload_cover_box'), // ... or DOM Element itself
		url : '/Attachment/DocumentUpload',
		multipart_params:{thumbnail:0},
		flash_swf_url : '/js/plupload/Moxie.swf',
		silverlight_xap_url : '/js/plupload/Moxie.xap',
		multi_selection:false,
		file_data_name:"filedata",
		filters : {
			max_file_size : '50MB',
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"}
			]
		},

		init: {
			PostInit: function() {

			},

			FilesAdded: function(up, files) {
				uploader.start();
			},

			UploadProgress: function(up, file) {
			},
			FileUploaded:function(up, file, info)
			{
				// 每个文件上传成功后,处理相关的事情
				var data = eval('('+info.response+')');
				console.log(data);
				var html = '<img src="'+data.data.attach+'" width="150" height="100" />';
				html += '<input type="hidden" name="attachment_identity" value="'+data.data.document_identity+'" />';
				$("#click_upload_cover").html(html);
			},
			Error: function(up, err) {
				document.getElementById('click_upload_cover_error').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
			}
		}
	});
	uploader.init();
});