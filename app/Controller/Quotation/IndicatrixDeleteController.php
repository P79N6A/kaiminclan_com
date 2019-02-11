<?php
/**
 *
 * 删除科目
 *
 * 20180301
 *
 */
class IndicatrixDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'indicatrixId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeIndicatrixIds = $this->argument('indicatrixId');
		
		$groupInfo = $this->service('QuotationIndicatrix')->getIndicatrixInfo($removeIndicatrixIds);
		
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		$this->service('QuotationIndicatrix')->removeIndicatrixId($removeIndicatrixIds);
		
		$sourceTotal = count($indicatrixId);
		$successNum = count($removeIndicatrixIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>