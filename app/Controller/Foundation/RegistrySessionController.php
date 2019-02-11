<?php
/**
 *
 * 注册表编辑
 *
 * 20180301
 *
 */
class KeyboradSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'blockId'=>array('type'=>'digital','tooltip'=>'模块ID','default'=>0),
			'page_identity'=>array('type'=>'digital','tooltip'=>'页面ID'),
			'module_identity'=>array('type'=>'digital','tooltip'=>'模板ID'),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'blockclass'=>array('type'=>'string','tooltip'=>'接口','length'=>80),
			'setting'=>array('type'=>'string','tooltip'=>'参数','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'模块状态','default'=>PaginationBlockModel::PAGINATION_BLOCK_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$blockId = $this->argument('blockId');
		
		$setarr = array(
			'page_identity' => $this->argument('page_identity'),
			'module_identity' => $this->argument('module_identity'),
			'title' => $this->argument('title'),
			'blockclass' => $this->argument('blockclass'),
			'setting' => json_encode($this->argument('setting')),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
		if($blockId){
			$this->service('PaginationBlock')->update($setarr,$blockId);
		}else{
			
			if($this->service('PaginationBlock')->checkTitle($title)){
				
				$this->info('模块已存在',4001);
			}
			
			$this->service('PaginationBlock')->insert($setarr);
		}
	}
}
?>