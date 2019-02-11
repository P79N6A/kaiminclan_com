<?php
/**
 *
 * 接口启用
 *
 * 20180301
 *
 */
class JoggleEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'joggleId'=>array('type'=>'digital','tooltip'=>'接口ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$joggleId = $this->argument('joggleId');
		
		$joggleInfo = $this->service('FabricationJoggle')->getTemplateInfo($joggleId);
		if(!$joggleInfo){
			$this->info('接口不存在',4101);
		}
		
		if($joggleInfo['status'] == FabricationJoggleModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('FabricationJoggle')->update(array('status'=>FabricationJoggleModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$joggleId);
		}
		
		
	}
}
?>