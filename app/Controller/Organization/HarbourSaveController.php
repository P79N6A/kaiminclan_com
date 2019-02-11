<?php
/**
 *
 * 军衔编辑
 *
 * 20180301
 *
 */
class HarbourSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'harbourId'=>array('type'=>'digital','tooltip'=>'军衔ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$harbourId = $this->argument('harbourId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'content' => $this->argument('content')
		);
		
		if($harbourId){
			$this->service('OrganizationHarbour')->update($setarr,$harbourId);
		}else{
			
			if($this->service('OrganizationHarbour')->checkHarbourTitle($setarr['title'])){
				
				$this->info('军衔已存在',4001);
			}
			
			$this->service('OrganizationHarbour')->insert($setarr);
		}
	}
}
?>