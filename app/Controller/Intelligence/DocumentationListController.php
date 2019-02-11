<?php
/**
 *
 * 文章列表
 *
 * 20180301
 *
 */
class DocumentationListController extends Controller {
	
	protected $permission = 'public';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'栏目','default'=>0),
			'start'=>array('type'=>'digital','tooltip'=>'开始','default'=>1),
			'perpage'=>array('type'=>'digital','tooltip'=>'数量','default'=>10),
			'kw'=>array('type'=>'doc','tooltip'=>'关键字','default'=>'')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		$start = $this->argument('start');
		$perpage = $this->argument('perpage');
		$kw = $this->argument('kw');
		
		$order = 'identity desc';
		$where = array();
		
		$where['status'] = IntelligenceDocumentationModel::INTELLIGENCE_DOCUMENTATION_STATUS_ENABLE;
		
		if($catalogueId){
			$where['catalogue_identity'] = $catalogueId;
		}
		
		$listdata = $this->service('IntelligenceDocumentation')->getDocumentationList($where,$start,$perpage,$order);
		
		$this->assign('list',$listdata['list']);
		$this->assign('start',$start);
		$this->assign('perpage',$perpage);
		$this->assign('total',$listdata['total']);
		
		
	}
}
?>