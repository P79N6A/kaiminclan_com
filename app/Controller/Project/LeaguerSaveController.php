<?php
/**
 *
 * 成员编辑
 *
 * 20180301
 *
 */
class LeaguerSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'leaguerId'=>array('type'=>'digital','tooltip'=>'成员ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'digital','tooltip'=>'介绍'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
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
		
		$leaguerId = $this->argument('leaguerId');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'subject_identity' => $this->argument('subject_identity'),
			'currency_identity' => $this->argument('currency_identity'),
			'account_identity' => $this->argument('account_identity'),
			'remark' => $this->argument('remark'),
		);
		
		if($leaguerId){
			$this->service('ProjectLeaguer')->update($setarr,$leaguerId);
		}else{
			
			if($this->service('ProjectLeaguer')->checkLeaguerTitle($setarr['title'])){
				
				$this->info('成员已存在',4001);
			}
			
			$this->service('ProjectLeaguer')->insert($setarr);
		}
	}
}
?>