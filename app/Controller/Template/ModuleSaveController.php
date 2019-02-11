<?php
/**
 *
 * 模板编辑
 *
 * 20180301
 *
 */
class ModuleSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'moduleId'=>array('type'=>'digital','tooltip'=>'模板ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'theme_identity'=>array('type'=>'digital','tooltip'=>'主题'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'效果图','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'代码','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'模板状态','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$moduleId = $this->argument('moduleId');
		
		$setarr = array(
			'theme_identity' => $this->argument('theme_identity'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'title' => $this->argument('title'),
			'code' => $this->argument('code'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
		if($moduleId){
			$this->service('TemplateModule')->update($setarr,$moduleId);
		}else{
			
			if($this->service('TemplateModule')->checkModuleTitle($title,$setarr['theme_identity'])){
				
				$this->info('模板已存在',4001);
			}
			
			$this->service('TemplateModule')->insert($setarr);
		}
	}
}
?>