// JavaScript Document
$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Bankroll/AccountSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/bankroll/account.html";
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });

  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
  
  jQuery("#click_button_save").click(function(){
	jQuery("#accountStatus").val(0);
	jQuery("#catalogForm").submit();
	});
  
});