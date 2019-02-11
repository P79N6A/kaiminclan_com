$(function(){
  var $validator = jQuery("#catalogForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Permanent/IndebtednessSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/permanent/indebtedness.html";
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
  
	jQuery('#subject-wrapper').cxSelect({
		selects: ['first','second','three'],
			jsonName:"title",
			jsonValue: 'id',
		url: "/finance/mechanism_subject.json?t="+Date.parse(new Date())
	});
});