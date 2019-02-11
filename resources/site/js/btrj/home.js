$(function(){
	var swiper = new Swiper('.swiper-container', {
      pagination: {
        el: '.swiper-pagination',
      },
	  autoplay: true,//可选选项，自动滑动
    });
	$.get("/Wexin/WxAuthorize",function(result){
	    if(result.status == 200){
	        if(result.data.redirectUrl){
                window.location.href = result.data.redirectUrl;
            }
        }
        console.log(result);
    },'json');
});
