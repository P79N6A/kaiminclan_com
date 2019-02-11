<?php
/**
 *
 * 删除信号
 *
 * 20180301
 *
 */
class InvaluableDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'invaluableId'=>array('type'=>'digital','tooltip'=>'信号ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeInvaluableIds = $this->argument('invaluableId');
		
		$groupInfo = $this->service('QuotationInvaluable')->getInvaluableInfo($removeInvaluableIds);
		
		if(!$groupInfo){
			$this->info('信号不存在',4101);
		}
		
		$this->service('QuotationInvaluable')->removeInvaluableId($removeInvaluableIds);
		
		$sourceTotal = count($invaluableId);
		$successNum = count($removeInvaluableIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>