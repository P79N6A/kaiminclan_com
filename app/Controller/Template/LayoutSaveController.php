<?php
/**
 *
 * 布局编辑
 *
 * 20180301
 *
 */
class LayoutSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'layoutId'=>array('type'=>'digital','tooltip'=>'布局ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'theme_identity'=>array('type'=>'digital','tooltip'=>'主题'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'效果图','default'=>0),
			'code'=>array('type'=>'html','tooltip'=>'代码','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$layoutId = $this->argument('layoutId');
		
		$setarr = array(
			'attachment_identity' => $this->argument('attachment_identity'),
			'theme_identity' => $this->argument('theme_identity'),
			'title' => $this->argument('title'),
			'code' => $this->argument('code'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
		if($layoutId){
			$this->service('TemplateLayout')->update($setarr,$layoutId);
		}else{
			
			if($this->service('TemplateLayout')->checkLayoutTitle($title)){
				
				$this->info('布局已存在',4001);
			}
			
			$this->service('TemplateLayout')->insert($setarr);
		}
	}
}
?>