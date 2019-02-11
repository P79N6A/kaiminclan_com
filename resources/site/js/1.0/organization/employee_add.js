
$(function(){
	var companyId = $("#GlobalSettingCompanyId").val();
	$('#position').cxSelect({
		selects: ['department','quarters','position'],
			jsonName:"title",
			jsonValue: 'id',
		url: "/organization/position_"+companyId+".json?t="+new Date().getTime()
	});
	var districtId = $("#GlobalSettingDistrictId").val();
	$('#district').cxSelect({
		selects: ['province','city','county'],
			jsonName:"title",
			jsonValue: 'id',
		url: "/geography/"+districtId+".json?t="+new Date().getTime()
	});
	
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Organization/EmployeeSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/organization/employee.html";
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
  
});