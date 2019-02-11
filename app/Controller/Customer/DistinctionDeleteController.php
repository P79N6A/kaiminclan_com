<?php
/**
 *
 * 删除客户等级
 *
 * 20180301
 *
 */
class DistinctionDeleteController extends Controller {
	
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
		if(!is_array($distinctionId)){
			$distinctionInfo = array($distinctionInfo);
		}
		
		$removeGroupIds = array();
		foreach($distinctionInfo as $key=>$distinction){
				$removeGroupIds[] = $distinction['identity'];
		}
		
		$this->service('CustomerDistinction')->removeDistinctionId($removeGroupIds);
		
		$sourceTotal = count($distinctionId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>