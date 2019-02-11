$(function(){
	$("#basicForm").validate({
		highlight: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-error');
		},
		submitHandler:function(form){
		__AjaxCommon("/Passageway/AlleywaySave",$(form).serialize(),function(result){
				console.log(result);
				if(result.status == 200){
					window.location.href="/passageway/alleyway.html";
				}else{
					alert(result.msg);
				}
			},'json','POST');
		}
  });
  
});