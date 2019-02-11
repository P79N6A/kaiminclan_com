<?php
/**
 *
 * 删除成份
 *
 * 20180301
 *
 */
class ReconstituentDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'reconstituentId'=>array('type'=>'digital','tooltip'=>'成份ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$reconstituentId = $this->argument('reconstituentId');
		
		$reconstituentInfo = $this->service('FundReconstituent')->getReconstituentInfo($reconstituentId);
		
		if(!$reconstituentInfo){
			$this->info('成份不存在',4101);
		}
		
		if(!is_array($reconstituentId)){
			$reconstituentInfo = array($reconstituentInfo);
		}
		
		
		$removeReconstituentIds = array();
		foreach($reconstituentInfo as $key=>$reconstituent){
			$removeReconstituentIds[] = $reconstituent['identity'];
		}
		
		$this->service('FundReconstituent')->removeReconstituentId($removeReconstituentIds);
		
		$sourceTotal = count($reconstituentId);
		$successNum = count($removeReconstituentIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>