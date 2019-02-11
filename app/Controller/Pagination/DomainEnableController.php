<?php
/**
 *
 * 域名启用
 *
 * 20180301
 *
 */
class DomainEnableController extends Controller {
	
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
		
		if($groupInfo['status'] == FoundationDomainModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FoundationDomain')->update(array('status'=>FoundationDomainModel::PAGINATION_BLOCK_STATUS_ENABLE),$domainId);
		}
		
		
	}
}
?>