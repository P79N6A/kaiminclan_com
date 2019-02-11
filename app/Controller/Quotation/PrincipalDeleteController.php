<?php
/**
 *
 * 删除科目
 *
 * 20180301
 *
 */
class PrincipalDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'principalId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removePrincipalIds = $this->argument('principalId');
		
		$groupInfo = $this->service('QuotationPrincipal')->getPrincipalInfo($removePrincipalIds);
		
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		$this->service('QuotationPrincipal')->removePrincipalId($removePrincipalIds);
		
		$sourceTotal = count($principalId);
		$successNum = count($removePrincipalIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>