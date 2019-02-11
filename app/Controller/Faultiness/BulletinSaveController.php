<?php
/**
 *
 * 缺陷编辑
 *
 * 20180301
 *
 */
class BulletinSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bulletinId'=>array('type'=>'digital','tooltip'=>'缺陷ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'frontend_identity'=>array('type'=>'digital','tooltip'=>'页面','default'=>0),
			'url'=>array('type'=>'url','tooltip'=>'链接','default'=>0),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'附件','default'=>0),
			'weight'=>array('type'=>'digital','tooltip'=>'优先级'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bulletinId = $this->argument('bulletinId');
		$url = $this->argument('url');
		
		$setarr = array(
			'content' => $this->argument('content'),
			'title' => $this->argument('title'),
			'frontend_identity' => $this->argument('frontend_identity'),
			'attachment_identity' => json_encode($this->argument('attachment_identity')),
			'weight' => $this->argument('weight'),
			'remark' => $this->argument('remark'),
		);
		
		if($bulletinId){
			$this->service('FaultinessBulletin')->update($setarr,$bulletinId);
		}else{
			
			if($this->service('FaultinessBulletin')->checkBulletinTitle($setarr['title'])){
				
				$this->info('缺陷已存在',4001);
			}
			
			$this->service('FaultinessBulletin')->insert($setarr);
		}
	}
}
?>