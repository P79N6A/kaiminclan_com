<?php
/**
 *
 * 禁用概念
 *
 * 20180301
 *
 */
class ConceptDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'conceptId'=>array('type'=>'digital','tooltip'=>'概念ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$conceptId = $this->argument('conceptId');
		
		$groupInfo = $this->service('SecuritiesConcept')->getCatalogInfo($conceptId);
		if(!$groupInfo){
			$this->info('概念不存在',4101);
		}
		
		if($groupInfo['status'] == SecuritiesConceptModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('SecuritiesConcept')->update(array('status'=>SecuritiesConceptModel::PAGINATION_BLOCK_STATUS_DISABLED),$conceptId);
		}
	}
}
?>