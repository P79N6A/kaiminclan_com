

$(function(){
	$('#platform_box').cxSelect({
		selects: ['domain','platform'],
		jsonName:"title",
		jsonValue: 'id',
		url: "/pagination/platform.json?t="+new Date().getTime()
	});
  jQuery(".select2").select2({
    width: '100%',
    minimumResultsForSearch: -1
  });
	$(".click_button_disabled").click(function(){
		var dataId = $(this).attr("data-id");
		var dataUrl = $(this).attr("data-url");
		var dataHash = $("#click_form_hash").val();;
		__AjaxCommon(dataUrl,{paginationId:dataId,__hash__:dataHash},function(result){
			console.log(result);
			if(result.status == 200){
				window.location.reload();
			}else{
				alert(result.msg);
			}
		},'json','POST');
	});
	$(".click_button_enabled").click(function(){
		var dataId = $(this).attr("data-id");
		var dataUrl = $(this).attr("data-url");
		var dataHash = $("#click_form_hash").val();;
		__AjaxCommon(dataUrl,{paginationId:dataId,__hash__:dataHash},function(result){
			console.log(result);
			if(result.status == 200){
				window.location.reload();
			}else{
				alert(result.msg);
			}
		},'json','POST');
	});
	$(".click_button_remove").click(function(){
		var dataId = $(this).attr("data-id");
		var dataUrl = $(this).attr("data-url");
		var dataHash = $("#click_form_hash").val();;
		if(confirm("确实要执行此操作？")){
		__AjaxCommon(dataUrl,{paginationId:dataId,__hash__:dataHash},function(result){
				console.log(result);
				if(result.status == 200){
					window.location.reload();
				}else{
					alert(result.msg);
				}
			},'json','POST');
		}
	});
	
});
