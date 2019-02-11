<?php
/**
 *
 * 删除概念
 *
 * 20180301
 *
 */
class ConceptDeleteController extends Controller {
	
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
		if(!is_array($conceptId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('SecuritiesConcept')->removeCatalogId($removeGroupIds);
		
		$sourceTotal = count($conceptId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>