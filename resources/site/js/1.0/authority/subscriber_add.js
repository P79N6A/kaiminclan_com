
$(function(){
	$("#basicForm").validate({
		highlight: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-error');
		},
		submitHandler:function(form){
		__AjaxCommon("/Authority/SubscriberSave",$(form).serialize(),function(result){
				console.log(result);
				if(result.status == 200){
					window.location.href="/authority/subscriber.html";
				}else{
					alert(result.msg);
				}
			},'json','POST');
		}
  });
	jQuery('#role-box').cxSelect({
		selects: ['first','second','third'],
			jsonName:"title",
			jsonValue: 'id',
		url: "/authority/role.json?t="+Date.parse(new Date())
	});

  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
  

});