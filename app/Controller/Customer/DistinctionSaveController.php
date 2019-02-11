<?php
/**
 *
 * 客户等级编辑
 *
 * 20180301
 *
 */
class DistinctionSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'distinctionId'=>array('type'=>'digital','tooltip'=>'客户等级ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'html','tooltip'=>'介绍','length'=>2000),
			'maximum'=>array('type'=>'digital','tooltip'=>'最大积分'),
			'minimum'=>array('type'=>'digital','tooltip'=>'最小积分'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$distinctionId = $this->argument('distinctionId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'maximum' => $this->argument('maximum'),
			'minimum' => $this->argument('minimum'),
			'remark' => $this->argument('remark')
		);
		
		if($distinctionId){
			$this->service('CustomerDistinction')->update($setarr,$distinctionId);
		}else{
			
			if($this->service('CustomerDistinction')->checkDistinctionTitle($setarr['title'])){
				
				$this->info('客户等级已存在',4001);
			}
			
			$distinctionId = $this->service('CustomerDistinction')->insert($setarr);
		}
		
		$this->assign('distinctionId',$distinctionId);
	}
}
?>