$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Dealings/AdjustmentSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/dealings/adjustment.html";
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });
  
  jQuery("#click_button_draft").click(function(){});

  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
  
});