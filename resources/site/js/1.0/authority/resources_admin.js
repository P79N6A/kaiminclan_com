$(function(){
  var $validator = jQuery("#roleForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Auhtority/ResourcesSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/authority/role.html";
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });
  
  $("#click_button_cannel").click(function(){
	var dataType = $(this).attr();
	var dataId = $(this).attr();
	if(dataType && dataId){
		window.location.href="/authority/subscriber.html";
	}
  });
  
});