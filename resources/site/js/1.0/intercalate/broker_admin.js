
$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Intercalate/BrokerSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/intercalate/broker.html";
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });
	jQuery('#district-box').cxSelect({
		selects: ['continent','region','country'],
			jsonName:"title",
			jsonValue: 'id',
		url: "/geography/0.json?t="+Date.parse(new Date())
	});

  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
  
});
//封面图
$(function(){
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'click_upload_cover', // you can pass an id...
		container: document.getElementById('click_upload_cover_box'), // ... or DOM Element itself
		url : '/Resources/AttachmentUpload',
		multipart_params:{thumbnail:0},
		flash_swf_url : '/js/plupload/Moxie.swf',
		silverlight_xap_url : '/js/plupload/Moxie.xap',
		multi_selection:false,
		file_data_name:"filedata",
		filters : {
			max_file_size : '500kb',
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
				var html = '<img src="'+data.data.attach+'" width="150" height="100" />';
				html += '<input type="hidden" name="attachment_identity" value="'+data.data.attachment_identity+'" />';
				$("#click_upload_cover").html(html);
			},
			Error: function(up, err) {
				document.getElementById('click_upload_cover_error').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
			}
		}
	});
	uploader.init();
});