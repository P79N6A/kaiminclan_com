$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Dealings/ExpensesSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/dealings/expenses.html";
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
    jQuery('#cxselect-subject').cxSelect({
        selects: ['first','second','third'],
        jsonName:"title",
        jsonValue: 'id',
        url: "/finance/subject.json?t="+Date.parse(new Date())
    });
  
});