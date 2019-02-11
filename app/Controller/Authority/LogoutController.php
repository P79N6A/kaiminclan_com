<?php
/**
 *
 * 登出
 *
 * 20180301
 *
 */
class LogoutController extends Controller {
	
	protected $permission = 'user';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$uid = $this->session('uid');
		if($uid){
			$this->session()->destroy();
		}
	}
}
?>