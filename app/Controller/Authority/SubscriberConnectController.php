<?php
/**
 *
 * 登出
 *
 * 20180301
 *
 */
class SubscriberConnectController extends Controller {
	
	protected $permission = 'guest';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'platform'=>array('type'=>'letter','tooltip'=>'平台')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$platform = $this->argument('platform');
		
		$url = $this->service('AuthorityConnect')->getLoginUrl('/Authority/SubscriberConnect/platform/'.$platform);
		header('Location:'.$url);
	}
}
?>