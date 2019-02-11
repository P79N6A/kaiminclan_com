// JavaScript Document
jQuery(function(){
	jQuery("#click_button_logout").click(function(){
		if(confirm("确实要登出吗？")){		
			__AjaxCommon("/Authority/Logout",{__hash__:jQuery(this).attr("data-hash")},function(result){
				console.log(result);
				if(result.status == 200){
					window.location.href = "/admin/login.html";
				}else{
					console.log(result);
				}
			},'json','POST');
		}
	});
	
	if(window.location.href.indexOf("/login.html") < 1 ){
		__AjaxCommon("/Authority/UserInfo",{},function(result){
			if(result.status == 200){
				var userInfo = '<img src="/img/bracket/photos/loggeduser.png" alt="" />';
					userInfo += result.data.memdata.fullname;
					userInfo += '<span class="caret"></span>';
				jQuery("#user_box").html(userInfo);
			}
		},'json','GET');
	}
});