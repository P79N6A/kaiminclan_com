<?php
/**
 *
 * 转出编辑
 *
 * 20180301
 *
 */
class ExpensesSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'expensesId'=>array('type'=>'digital','tooltip'=>'转出ID','default'=>0),
			'account_identity'=>array('type'=>'digital','tooltip'=>'账户'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
			'amount'=>array('type'=>'money','tooltip'=>'金额','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$expensesId = $this->argument('expensesId');
		
		$setarr = array(
			'account_identity' => $this->argument('account_identity'),
			'subject_identity' => $this->argument('subject_identity'),
			'amount' => $this->argument('amount'),
			'remark' => $this->argument('remark')
		);
		
		
		if($expensesId){
			$this->service('BankrollExpenses')->update($setarr,$expensesId);
		}else{
			
			$result = $this->service('BankrollExpenses')->insert($setarr);
			if(!$result){
				$this->info('取款失败',400001);
			}
		}
	}
}
?>