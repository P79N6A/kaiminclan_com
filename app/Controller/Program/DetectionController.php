<?php
/***
 * 这是一个代码更新插件
 *
 * 开始->版本检测->读取文件信息->文件下载->权限验证->旧版备份->文件覆盖->结束
 */

class UpgradeController extends Controller
{
	
	public function fire(){
		$host = $_POST['host'];
		$app = $_POST['name'];
		$license = $_POST['license'];
		$sign = $_POST['sign'];

		$currentSign = md5($host.date('H').$license.date('Ymd'));
		if($currentSign != $sign){
			$response['status'] = 40001;
			$response['msg'] = '签名错误';
		}else{

			$response['data'] = array(
				'version'=>203,
				'filesize'=>1024*1024,
				'file'=>'http://www.kaiminclan.com/data/attachment/201802/05/'.$app.'_v2020.zip'
			);
		}

		echo json_encode($response);
	}
}


?>