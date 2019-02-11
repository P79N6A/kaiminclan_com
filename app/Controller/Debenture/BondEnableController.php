<?php
/**
 *
 * 债券启用
 *
 * 20180301
 *
 */
class BondEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bondId'=>array('type'=>'digital','tooltip'=>'债券ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bondId = $this->argument('bondId');
		
		$groupInfo = $this->service('DebentureBond')->getBondInfo($bondId);
		if(!$groupInfo){
			$this->info('债券不存在',4101);
		}
		
		if($groupInfo['status'] == DebentureBondModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('DebentureBond')->update(array('status'=>DebentureBondModel::PAGINATION_BLOCK_STATUS_ENABLE),$bondId);
		}
		
		
	}
}
?>