<?php
/**
 *
 * 调账编辑
 *
 * 20180301
 *
 */
class AdjustmentSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'adjustmentId'=>array('type'=>'digital','tooltip'=>'调账ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'amount'=>array('type'=>'money','tooltip'=>'金额','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$adjustmentId = $this->argument('adjustmentId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'remark' => $this->argument('remark')
		);
		
		if($adjustmentId){
			$this->service('BankrollAdjustment')->update($setarr,$adjustmentId);
		}else{
			
			$this->service('BankrollAdjustment')->insert($setarr);
		}
	}
}
?>