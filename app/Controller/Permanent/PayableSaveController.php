<?php
/**
 *
 * 应付款编辑
 *
 * 20180301
 *
 */
class PayableSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'payableId'=>array('type'=>'digital','tooltip'=>'应付款ID','default'=>0),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
			'expire_date'=>array('type'=>'date','format'=>'dateline','tooltip'=>'到期时间'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$payableId = $this->argument('payableId');
		
		$setarr = array(
			'amount' => $this->argument('amount'),
			'title' => $this->argument('title'),
			'expire_date' => $this->argument('expire_date'),
			'remark' => $this->argument('remark')
		);
		
		if($payableId){
			$this->service('PermanentPayable')->update($setarr,$payableId);
		}else{
			
			if($this->service('PermanentPayable')->checkPayableTitle($setarr['title'])){
				
				$this->info('此应付款已存在',4001);
			}
			
			$this->service('PermanentPayable')->insert($setarr);
		}
	}
}
?>