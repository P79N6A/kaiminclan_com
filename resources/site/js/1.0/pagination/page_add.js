

$(function(){
	$('#catalogue').cxSelect({
		selects: ['domain','platform','catalogue'],
		jsonName:"title",
		jsonValue: 'identity',
		url: "/pagination/catalogue.json?t="+new Date().getTime()
	});
	$("#basicForm").validate({
		highlight: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
		  jQuery(element).closest('.form-group').removeClass('has-error');
		},
		submitHandler:function(form){
		__AjaxCommon("/Pagination/PageSave",$(form).serialize(),function(result){
				console.log(result);
				if(result.status == 200){
					window.location.href="/pagination/page.html";
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
	$("#click_setting_add").click(function(){
		var cnt = parseInt($(this).attr("data-cnt"));
		if(cnt == NaN){
			cnt = 1;
		}else{
			cnt +=1;
		}
		var html = '<tr><td><input type="text" name="setting['+cnt+'][code]" class="form-control"></td>';
		html += '<td><input type="text" name="setting['+cnt+'][type]" class="form-control"></td>';
		html += '<td><input type="text" name="setting['+cnt+'][tooltip]" class="form-control"></td>';
		html += '<td><input type="text" name="setting['+cnt+'][value]" class="form-control"></td></tr>';
		$("#setting_form_box").append(html);
		$(this).attr("data-cnt",cnt);
	});


});
