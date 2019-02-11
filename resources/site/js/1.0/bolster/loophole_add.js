
$(function(){
	$("#basicForm").validate({
		highlight: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-error');
		},
		submitHandler:function(form){
		__AjaxCommon("/Bolster/loopholeSave",$(form).serialize(),function(result){
				console.log(result);
				if(result.status == 200){
					window.location.href="/bolster/loophole.html";
				}else{
					alert(result.msg);
				}
			},'json','POST');
		}
  });

});