<?php
/**
 *
 * 目标编辑
 *
 * 20180301
 *
 */
class ProjectSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'projectId'=>array('type'=>'digital','tooltip'=>'目标ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'id'=>array('type'=>'digital','tooltip'=>'类型ID','default'=>0),
			'idtype'=>array('type'=>'digital','tooltip'=>'目标类型','default'=>0),
			'happen_date'=>array('type'=>'date','format'=>'dateline','tooltip'=>'收款时间'),
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
		
		$projectId = $this->argument('projectId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'idtype' => $this->argument('idtype'),
			'id' => $this->argument('id'),
			'happen_date' => $this->argument('happen_date'),
			'subject_identity' => $this->argument('subject_identity'),
			'currency_identity' => $this->argument('currency_identity'),
			'account_identity' => $this->argument('account_identity'),
			'remark' => $this->argument('remark'),
		);
		
		if($projectId){
			$this->service('BudgetProject')->update($setarr,$projectId);
		}else{
			
			$this->service('BudgetProject')->insert($setarr);
		}
	}
}
?>