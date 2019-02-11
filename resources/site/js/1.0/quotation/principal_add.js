$(function(){
  var $validator = jQuery("#roleForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Quotation/PrincipalSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/quotation/principal.html";
			}else{
				alert(result.msg);
			}
		},'json','POST');
	}
  });
  
	jQuery('#program').cxSelect({
		selects: ['application','functional'],
			jsonName:"title",
			jsonValue: 'id',
		url: "/program/application.json?t="+new Date().getTime()
	});

  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
  
});