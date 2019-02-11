$(function(){
	$(".click_button_disabled").click(function(){
		var dataId = $(this).attr("data-id");
		__AjaxCommon("/Intelligence/OriginateDisabled",{originateId:dataId,__hash__:"{__HASH__}"},function(result){
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
		__AjaxCommon("/Intelligence/OriginateDisabled",{originateId:dataId,__hash__:"{__HASH__}"},function(result){
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
			__AjaxCommon("/Intelligence/OriginateDelete",{originateId:dataId,__hash__:"{__HASH__}"},function(result){
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