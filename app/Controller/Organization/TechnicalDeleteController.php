<?php
/**
 *
 * 删除职称
 *
 * 20180301
 *
 */
class TechnicalDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'technicalId'=>array('type'=>'digital','tooltip'=>'职称ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeTechnicalIds = $this->argument('technicalId');
		
		$technicalInfo = $this->service('OrganizationTechnical')->getTechnicalInfo($removeTechnicalIds);
		
		if(!$technicalInfo){
			$this->info('职称不存在',4101);
		}
		
		$this->service('OrganizationTechnical')->removeTechnicalId($removeTechnicalIds);
		
		$sourceTotal = count($technicalueId);
		$successNum = count($removeTechnicalIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>