<?php
/**
 *
 * 项目编辑
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
			'projectId'=>array('type'=>'digital','tooltip'=>'项目ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'html','tooltip'=>'介绍'),
			'industry_identity'=>array('type'=>'digital','tooltip'=>'产业'),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'货币'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'approval_date'=>array('type'=>'date','tooltip'=>'立项时间','format'=>'dateline'),
			'expire_date'=>array('type'=>'date','tooltip'=>'到期时间','format'=>'dateline'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$projectId = $this->argument('projectId');
		
		$setarr = array(
			'industry_identity' => $this->argument('industry_identity'),
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'currency_identity' => $this->argument('currency_identity'),
			'amount' => $this->argument('amount'),
			'approval_date' => $this->argument('approval_date'),
			'expire_date' => $this->argument('expire_date'),
			'remark' => $this->argument('remark'),
		);
		
		if($projectId){
			$this->service('InvestmentProject')->update($setarr,$projectId);
		}else{
			
			if($this->service('InvestmentProject')->checkProjectTitle($setarr['title'])){
				
				$this->info('项目已存在',4001);
			}
			
			$this->service('InvestmentProject')->insert($setarr);
		}
	}
}
?>