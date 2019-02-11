$(function(){
	var validateOptions = {
		highlight: function(element) {
			jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
		},
		submitHandler:function(form){
			__AjaxCommon("/Fund/ProductSave",$(form).serialize(),function(result){
				console.log(result);
				if(result.status == 200){
					window.location.href="/fund/reconstituent.html";
				}else{
					alert(result.msg);
				}
			},'json','POST');
		}
	};
	
	var $validator = jQuery("#catalogForm").validate(validateOptions);

	jQuery(".select2").select2({
		width: '100%',
		minimumResultsForSearch: -1
	});
  
});