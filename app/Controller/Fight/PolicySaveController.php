<?php
/**
 *
 * 产品编辑
 *
 * 20180301
 *
 */
class PolicySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'policyId'=>array('type'=>'digital','tooltip'=>'产品ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'channel_identity'=>array('type'=>'digital','tooltip'=>'类型'),
			'content'=>array('type'=>'html','tooltip'=>'策略信息','length'=>2000),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>'')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$policyId = $this->argument('policyId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'channel_identity' => $this->argument('channel_identity'),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark')
		);
		
		if($policyId){
			$this->service('FightPolicy')->update($setarr,$policyId);
		}else{
			
			if($this->service('FightPolicy')->checkTitle($setarr['title'])){
				
				$this->info('产品已存在',4001);
			}
			
			$this->service('FightPolicy')->insert($setarr);
		}
	}
}
?>