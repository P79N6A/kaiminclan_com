<?php
/**
 *
 * 模块编辑
 *
 * 20180301
 *
 */
class ThemeSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'themeId'=>array('type'=>'digital','tooltip'=>'模块ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$themeId = $this->argument('themeId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
		if($themeId){
			$this->service('TemplateTheme')->update($setarr,$themeId);
		}else{
			
			if($this->service('TemplateTheme')->checkThemeTitle($title)){
				
				$this->info('模块已存在',4001);
			}
			
			$this->service('TemplateTheme')->insert($setarr);
		}
	}
}
?>