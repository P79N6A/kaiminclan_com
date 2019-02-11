<?php
/**
 *
 * 公司启用
 *
 * 20180301
 *
 */
class CapitalEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'capitalId'=>array('type'=>'digital','tooltip'=>'公司ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$capitalId = $this->argument('capitalId');
		
		$capitalInfo = $this->service('PropertyCapital')->getCapitalInfo($capitalId);
		if(!$capitalInfo){
			$this->info('公司不存在',4101);
		}
		
		if($capitalInfo['status'] == PropertyCapitalModel::BILLBOARD_CATALOGUE_STATUS_DISABLED){
			$this->service('PropertyCapital')->update(array('status'=>PropertyCapitalModel::BILLBOARD_CATALOGUE_STATUS_ENABLE),$capitalId);
		}
		
		
	}
}
?>