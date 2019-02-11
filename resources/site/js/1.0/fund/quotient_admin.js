
$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Fund/QuotientSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/fund/quotient.html";
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });
  
  $("#click_quantity_change,#click_univalent_change").change(function(){
		var quantity = $("#click_quantity_change").val();
		var univalent = $("#click_univalent_change").val();
		$("#click_amount_change").val(quantity*univalent);
  });

  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
  
});