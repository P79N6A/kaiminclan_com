$(function(){
	
	jQuery("ul.tab li").click(function(){
		jQuery("ul.tab li").removeClass("hover");
		jQuery(this).addClass("hover");
		var dataType = jQuery(this).attr("data-type");
		jQuery(".item").removeClass("show");
		jQuery("."+dataType).addClass("show");
	});
});