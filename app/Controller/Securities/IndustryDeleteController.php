<?php
/**
 *
 * 删除调账
 *
 * 20180301
 *
 */
class IndustryDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'industryId'=>array('type'=>'digital','tooltip'=>'调账ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$industryId = $this->argument('industryId');
		
		$groupInfo = $this->service('SecuritiesIndustry')->getIndustryInfo($industryId);
		
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		if(!is_array($industryueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('SecuritiesIndustry')->removeIndustryId($removeGroupIds);
		
		$sourceTotal = count($industryueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>