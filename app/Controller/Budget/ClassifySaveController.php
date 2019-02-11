<?php
/**
 *
 * 分类编辑
 *
 * 20180301
 *
 */
class ClassifySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'classifyId'=>array('type'=>'digital','tooltip'=>'分类ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'happen_date'=>array('type'=>'date','format'=>'dateline','tooltip'=>'分类时间'),
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
		
		$classifyId = $this->argument('classifyId');
		
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
		
		if($classifyId){
			$this->service('BudgetClassify')->update($setarr,$classifyId);
		}else{
			
			if($this->service('BudgetClassify')->checkClassifyTitle($setarr['title'])){
				
				$this->info('分类已存在',4001);
			}
			
			$this->service('BudgetClassify')->insert($setarr);
		}
	}
}
?>