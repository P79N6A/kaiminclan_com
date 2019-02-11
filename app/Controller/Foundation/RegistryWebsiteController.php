<?php
/**
 *
 * 注册表编辑
 *
 * 20180301
 *
 */
class RegistryWebsiteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'domain'=>array('type'=>'string','tooltip'=>'域名'),
			'icp'=>array('type'=>'string','tooltip'=>'ICP备案信息'),
			'seotitle'=>array('type'=>'string','tooltip'=>'SEO标题'),
			'seokeyword'=>array('type'=>'string','tooltip'=>'SEO关键字'),
			'seodescription'=>array('type'=>'string','tooltip'=>'SEO描述'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		
		$website = array();
		$website['title'] = $this->argument('title');
		$website['domain'] = $this->argument('domain');
		$website['icp'] = $this->argument('icp');
		$website['seotitle'] = $this->argument('seotitle');
		$website['seokeyword'] = $this->argument('seokeyword');
		$website['seodescription'] = $this->argument('seodescription');
		
		$this->service('FoundationRegistryWebsite')->save($website);
	}
}
?>