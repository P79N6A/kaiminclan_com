<?php
/**
 *
 * 删除域名
 *
 * 20180301
 *
 */
class DomainDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'domainId'=>array('type'=>'digital','tooltip'=>'域名ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$domainId = $this->argument('domainId');
		
		$groupInfo = $this->service('FoundationDomain')->getDomainInfo($domainId);
		
		if(!$groupInfo){
			$this->info('域名不存在',4101);
		}
		if(!is_array($domainId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('FoundationDomain')->removeDomainId($removeGroupIds);
		
		$sourceTotal = count($domainId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>