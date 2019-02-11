<?php
/**
 *
 * 支出编辑
 *
 * 20180301
 *
 */
class ExpensesSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'expensesId'=>array('type'=>'digital','tooltip'=>'支出ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'position_identity'=>array('type'=>'digital','tooltip'=>'岗位'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$expensesId = $this->argument('expensesId');
		
		$setarr = array(
			'position_identity' => $this->argument('position_identity'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'content' => $this->argument('content')
		);
		
		if($expensesId){
			$this->service('DealingsExpenses')->update($setarr,$expensesId);
		}else{
			
			if($this->service('DealingsExpenses')->checkTitle($setarr['title'],$setarr['position_identity'])){
				
				$this->info('支出已存在',4001);
			}
			
			$this->service('DealingsExpenses')->insert($setarr);
		}
	}
}
?>