<?php
/**
 *
 * 删除债券
 *
 * 20180301
 *
 */
class BondDeleteController extends Controller {
	
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
		
		$bondInfo = $this->service('DebentureBond')->getBondInfo($bondId);
		
		if(!$bondInfo){
			$this->info('债券不存在',4101);
		}
		if(!is_array($bondId)){
			$bondInfo = array($bondInfo);
		}
		
		$removeBondIds = array();
		foreach($bondInfo as $key=>$bond){
				$removeBondIds[] = $bond['identity'];
		}
		
		$this->service('DebentureBond')->removeBondId($removeBondIds);
		
		$sourceTotal = count($bondId);
		$successNum = count($removeBondIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>