// JavaScript Document
$(function(){
  var $validator = jQuery("#baseForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Authority/Login",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				if(result.data.hasOwnProperty("returnUrl") && result.data.returnUrl != ""){
					refererUrl = result.data.returnUrl;
				}

				window.location.href=refererUrl;
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });
  
});
$(function(){
	$("body").attr("class","signin");
})