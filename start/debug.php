<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HTTP接口调试</title>
</head>
<script type="text/javascript" src="/js/jquery.js"></script>
<style type="text/css">
.wrapper { margin:0 auto; width:720px;  }
.block { border:#999 1px solid; padding:10px 25px; }
.block table { margin-top:20px; border-collapse:collapse; background:#ededed; }
.block table tr th { background:#d1d1d1; }
.block table tr td { padding:5px 10px; border:#CCC 1px solid;}
.block table tr td input { border:#CCC 1px solid; padding:5px; width:90%;}
.block table tr td textarea { border:#CCC 1px solid; padding:5px; width:98%;}

.click_api_send { width:200px; padding:7px 50px; background:#48a474; color:#fff; border:#ededed 1px solid; font-size:16px; margin:0 auto; margin-top:25px; }
</style>
<body>
<div class="wrapper">
<h1>在线HTTP POST/GET接口测试工具</h1>
<div class="block">
<table width="100%">
  <tr>
    <td width="5%">
      <select name="select" id="sendMethod">
        <option value="1">POST</option>
        <option value="0">GET</option>
    </select></td>
    <td width="95%"><input type="text" name="textfield" id="sendUrl" /></td>
  </tr>
</table>
<table width="100%">
  <thead>
    <tr>
      <td width="20%">Body参数</td>
      <td width="80%">Body值</td>
    </tr>
  </thead>
  <tbody id="body">
    <tr>
        <td><input type="text" class="bodyField" name="body['field'][]" /></td>
        <td>
        	<input type="text" class="bodyValue" name="body['value'][]" style="width:60%" />
            <input type="button" name="" value="删除参数" style="width:15%" />
        </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><input type="button" name="addBody" id="addBody" value="添加参数" /></td>
    </tr>
  </tfoot>
</table>
<script type="text/javascript">
$(function(){
	var bodyCnt = 1;
	var elementId = 'body_';
	$("#addBody").click(function(){
		bodyCnt++;
		elementId = 'body_'+bodyCnt;
		var html = '<tr id="'+elementId+'">';
		html += '<td><input type="text" class="bodyField" name="body_field\'][]" /></td>';
		html += '<td><input type="text" class="bodyValue" name="body[\'value\'][]" style="width:60%" />';
		html += '<input type="button" name="del" onclick="delField(\''+elementId+'\')" value="删除参数" style="width:15%" />';
		html += '</td>';
		html += '</tr>';
		$("#body").append(html);
	});
});
function delField(elementId){
	$("#"+myTrim(elementId)).remove();
}
function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}
</script>
<table width="100%">
	<thead>
      <tr>
        <td width="20%">Header名称</td>
        <td width="80%">Header值</td>
      </tr>
  </thead>
  <tbody id="header">
    <tr>
        <td><input type="text" class="headerField" name="header['field'][]" /></td>
        <td><input type="text" class="headerValue" name="header['value'][]" style="width:60%" />
            <input type="button" name="" value="删除参数" style="width:15%" />
        </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><input type="button" name="addHeader" id="addHeader" value="添加Header" /></td>
    </tr>
  </tfoot>
</table>
<script type="text/javascript">
$(function(){
	var bodyCnt = 1;
	var elementId = 'body_';
	$("#addHeader").click(function(){
		elementId = 'header_'+bodyCnt;
		var html = '<tr id="'+elementId+'">';
		html += '<td><input type="text" class="headerField" name="header[\'field\'][]" /></td>';
		html += '<td><input type="text" class="headerValue" name="header[\'value\'][]" style="width:60%" />';
		html += '<input type="button" name="del" onclick="delField(\''+elementId+'\')" value="删除参数" style="width:15%" />';
		html += '</td>';
		html += '</tr>';
		$("#header").append(html);
	});
});
</script>
<table width="100%">
	<thead>
      <tr>
        <td>Response Body</td>
        </tr>
  </thead>
  <tbody>
    <tr>
      <td><textarea name="textarea" id="responseBody" cols="45" rows="5"></textarea></td>
      </tr>
    </tbody>
  <tfoot>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </tfoot>
</table>
<input class="click_api_send" type="button" name="button" id="click_api_send" value="发送请求" />
</div>
</div>

<script type="text/javascript">
function getHeader(){
	var optionsVal = {"content-type":"application/x-www-form-urlencoded"};
	$(".headerField").each(function(index, element) {
		if($(this).val().length > 0){
			optionsVal[$(this).val()] = $(".headerValue").eq(index).val();
		}
    });
	return optionsVal;
}
function getParam(){
	var optionsVal = {};
	$(".bodyField").each(function(index, element) {
		if($(this).val().length > 0){
			optionsVal[$(this).val()] = $(".bodyValue").eq(index).val(); 
		}
    });
	return optionsVal;
}
$(function(){
	$("#click_api_send").click(function(){
		var sendMethod = $("#sendMethod").val();
		var sendUrl = $("#sendUrl").val();
		if(sendUrl.length > 0){
			if(sendUrl.indexOf("http:",0) < 0 || sendUrl.indexOf("https:",0) < 0){
				//sendUrl = "http://"+sendUrl;
			}
			if(sendMethod){
				sendMethod = "POST";
			}else{
				sendMethod = "GET";
			}
			$("#responseBody").val('稍后，正在处理..');
			$.ajax({
				//请求类型，这里为POST
				 type: sendMethod,
				 //你要请求的api的URL
				 url: sendUrl ,
				 //是否使用缓存
				 cache:false,
				 //数据类型，这里我用的是json
				 dataType: "json", 
				 //必要的时候需要用JSON.stringify() 将JSON对象转换成字符串
				 data: getParam(), //data: {key:value}, 
				 //添加额外的请求头
				 headers : getHeader(),
				 //请求成功的回调函数
				 success: function(result){
					$("#responseBody").val(JSON.stringify(result));
				 },
				 error:function(result){
					$("#responseBody").val(JSON.stringify(result));
				}
			});
		}
	});
});
</script>
</body>
</html>