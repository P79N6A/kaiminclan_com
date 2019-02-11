<?php
/**
 *
 * 文章信息
 *
 * 20180301
 *
 */
class DocumentationDetailController extends Controller {
	
	protected $permission = 'public';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentationId'=>array('type'=>'digital','tooltip'=>'栏目'),
			'start'=>array('type'=>'digital','tooltip'=>'数量','default'=>1)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$documentationId = $this->argument('documentationId');
		$start = $this->argument('start');
		
		$order = 'identity desc';
		$where = array();
		
		$where['status'] = IntelligenceDocumentationModel::INTELLIGENCE_DOCUMENTATION_STATUS_ENABLE;
		
		$where['identity'] = $documentationId;
		$documentationData = $this->service('IntelligenceDocumentation')->getDocumentationInfo($where);
		if(!$documentationData){
			$this->info('文章不能存在',400004);
		}
		
		$substanceData = $this->service('IntelligenceSubstance')->getSubstanceInfoByDocumentationId($documentationId,$start);
		
		$this->assign('article',$documentationData);
		$this->assign('substance',$substanceData);
		$this->assign('start',$start);
		$this->assign('total',$listdata['total']);
		
		
	}
}
?>