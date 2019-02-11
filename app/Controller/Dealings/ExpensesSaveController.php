<?php
/**
 *
 * 支出编辑
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
			'expensesId'=>array('type'=>'digital','tooltip'=>'支出ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'id'=>array('type'=>'digital','tooltip'=>'类型ID','default'=>0),
			'idtype'=>array('type'=>'digital','tooltip'=>'支出类型','default'=>0),
			'happen_date'=>array('type'=>'date','format'=>'dateline','tooltip'=>'收款时间'),
            'first_subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
            'second_subject_identity'=>array('type'=>'digital','tooltip'=>'科目','default'=>0),
            'third_subject_identity'=>array('type'=>'digital','tooltip'=>'科目','default'=>0),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'货币'),
			'account_identity'=>array('type'=>'digital','tooltip'=>'账户'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$expensesId = $this->argument('expensesId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'idtype' => $this->argument('idtype'),
			'id' => $this->argument('id'),
			'happen_date' => $this->argument('happen_date'),
            'first_subject_identity' => $this->argument('first_subject_identity'),
            'second_subject_identity' => $this->argument('second_subject_identity'),
            'third_subject_identity' => $this->argument('third_subject_identity'),
			'currency_identity' => $this->argument('currency_identity'),
			'account_identity' => $this->argument('account_identity'),
			'remark' => $this->argument('remark'),
		);
		
		if($expensesId){
			$this->service('DealingsExpenses')->update($setarr,$expensesId);
		}else{
			
			$this->service('DealingsExpenses')->insert($setarr);
		}
	}
}
?>