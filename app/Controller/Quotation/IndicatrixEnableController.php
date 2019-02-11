<?php
/**
 *
 * 科目启用
 *
 * 20180301
 *
 */
class IndicatrixEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'indicatrixId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$indicatrixId = $this->argument('indicatrixId');
		
		$groupInfo = $this->service('QuotationIndicatrix')->getIndicatrixInfo($indicatrixId);
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		if($groupInfo['status'] == QuotationIndicatrixModel::QUOTATION_PRINCIPAL_STATUS_DISABLED){
			$this->service('QuotationIndicatrix')->update(array('status'=>QuotationIndicatrixModel::QUOTATION_PRINCIPAL_STATUS_ENABLE),$indicatrixId);
		}
		
		
	}
}
?>