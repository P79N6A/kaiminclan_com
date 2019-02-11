<?php
/**
 *
 * 删除监管
 *
 * 20180301
 *
 */
class SuperviseDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'superviseId'=>array('type'=>'digital','tooltip'=>'监管ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeSuperviseIds = $this->argument('superviseId');
		
		$groupInfo = $this->service('IntercalateSupervise')->getSuperviseInfo($removeSuperviseIds);
		
		if(!$groupInfo){
			$this->info('监管不存在',4101);
		}
		
		$this->service('IntercalateSupervise')->removeSuperviseId($removeSuperviseIds);
		
		$sourceTotal = count($superviseId);
		$successNum = count($removeSuperviseIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>