<?php
/**
 *
 * 需求编辑
 *
 * 20180301
 *
 */
class DemandSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'demandId'=>array('type'=>'digital','tooltip'=>'需求ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'html','tooltip'=>'需求说明','length'=>2000),
			'clientete_identity'=>array('type'=>'digital','tooltip'=>'联系人'),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'需求类型','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$demandId = $this->argument('demandId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'clientete_identity' => $this->argument('clientete_identity'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($demandId){
			$this->service('RequirementDemand')->update($setarr,$demandId);
		}else{
			
			if($this->service('RequirementDemand')->checkDemandTitle($setarr['title'],$setarr['clientete_identity'])){
				
				$this->info('需求已存在',4001);
			}
			
			$demandId = $this->service('RequirementDemand')->insert($setarr);
		}
		
		$this->assign('demandId',$demandId);
	}
}
?>