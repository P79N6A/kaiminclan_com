<?php
/**
 *
 * 域名编辑
 *
 * 20180301
 *
 */
class DomainInitialController extends Controller {
	
	protected $permission = 'public';
	protected $method = 'post';
	
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
		$this->service('PaginationPage')->pushRoute();
	}
}
?>