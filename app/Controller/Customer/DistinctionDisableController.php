<?php
/**
 *
 * 禁用客户等级
 *
 * 20180301
 *
 */
class DistinctionDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'distinctionId'=>array('type'=>'digital','tooltip'=>'客户等级ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$distinctionId = $this->argument('distinctionId');
		
		$distinctionInfo = $this->service('CustomerDistinction')->getDistinctionInfo($distinctionId);
		if(!$distinctionInfo){
			$this->info('客户等级不存在',4101);
		}
		
		if($distinctionInfo['status'] == CustomerDistinctionModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('CustomerDistinction')->update(array('status'=>CustomerDistinctionModel::PAGINATION_BLOCK_STATUS_DISABLED),$distinctionId);
		}
	}
}
?>