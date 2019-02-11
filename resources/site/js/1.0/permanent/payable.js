$(function(){
	var formHash = $("#settingFormHash").val();
	$(".click_button_disabled").click(function(){
		var dataId = $(this).attr("data-id");
		__AjaxCommon("/Permanent/PayableDisabled",{payableId:dataId,__hash__:formHash},function(result){
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
		__AjaxCommon("/Permanent/PayableDisabled",{payableId:dataId,__hash__:formHash},function(result){
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
		if(confirm("确实要执行此操作？")){
			__AjaxCommon("/Permanent/PayableDelete",{payableId:dataId,__hash__:formHash},function(result){
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