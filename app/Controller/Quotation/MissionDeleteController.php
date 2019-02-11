<?php
/**
 *
 * 删除信号
 *
 * 20180301
 *
 */
class SignalDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'superviseId'=>array('type'=>'digital','tooltip'=>'信号ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeSignalIds = $this->argument('superviseId');
		
		$groupInfo = $this->service('QuotationSignal')->getSignalInfo($removeSignalIds);
		
		if(!$groupInfo){
			$this->info('信号不存在',4101);
		}
		
		$this->service('QuotationSignal')->removeSignalId($removeSignalIds);
		
		$sourceTotal = count($superviseId);
		$successNum = count($removeSignalIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>