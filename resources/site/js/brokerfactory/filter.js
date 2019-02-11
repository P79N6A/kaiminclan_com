$(function(){
	var searchOptions = 
	{
		highlight: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-error');
		},
		submitHandler:function(form){
			var dataType = jQuery("#filterType").val();
			console.log(form);
			console.log(form.kw.value);
			window.location.href="/broker/search.html?mod="+dataType+"&kw="+form.kw.value;
		}
	};
	jQuery("#filterForm").validate(searchOptions);
	
	jQuery("ul.menu li").click(function(){
		jQuery("ul.menu li").removeClass("curr");
		jQuery(this).addClass("curr");
		var dataType = jQuery(this).attr("data-type");
		jQuery("#filterType").val(dataType);
	});
});