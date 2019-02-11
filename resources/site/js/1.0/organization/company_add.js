
$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Organization/CompanySave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/organization/company.html";
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