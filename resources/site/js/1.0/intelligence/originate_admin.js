$(function(){
  var $validator = jQuery("#roleForm").validate({
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error');
    },
	submitHandler:function(form){
		__AjaxCommon("/Intelligence/OriginateSave",$(form).serialize(),function(result){
			console.log(result);
			if(result.status == 200){
				window.location.href="/intelligence/originate.html";
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