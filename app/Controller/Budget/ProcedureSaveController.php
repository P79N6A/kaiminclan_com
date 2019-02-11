<?php
/**
 *
 * 执行编辑
 *
 * 20180301
 *
 */
class ProcedureSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'procedureId'=>array('type'=>'digital','tooltip'=>'执行ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'happen_date'=>array('type'=>'date','format'=>'dateline','tooltip'=>'执行时间'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'货币'),
			'account_identity'=>array('type'=>'digital','tooltip'=>'账户'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$procedureId = $this->argument('procedureId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'happen_date' => $this->argument('happen_date'),
			'subject_identity' => $this->argument('subject_identity'),
			'currency_identity' => $this->argument('currency_identity'),
			'account_identity' => $this->argument('account_identity'),
			'remark' => $this->argument('remark'),
		);
		
		if($procedureId){
			$this->service('BudgetProcedure')->update($setarr,$procedureId);
		}else{
			
			if($this->service('BudgetProcedure')->checkProcedureTitle($setarr['title'])){
				
				$this->info('执行已存在',4001);
			}
			
			$this->service('BudgetProcedure')->insert($setarr);
		}
	}
}
?>