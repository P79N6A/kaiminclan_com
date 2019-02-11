<?php
/**
 *
 * 漏洞启用
 *
 * 20180301
 *
 */
class LoopholeEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'loopholeId'=>array('type'=>'digital','tooltip'=>'漏洞ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$loopholeId = $this->argument('loopholeId');
		
		$loopholeInfo = $this->service('BolsterLoophole')->getTemplateInfo($loopholeId);
		if(!$loopholeInfo){
			$this->info('漏洞不存在',4101);
		}
		
		if($loopholeInfo['status'] == BolsterLoopholeModel::BOLSTER_LOOPHOLEE_STATUS_DISABLED){
			$this->service('BolsterLoophole')->update(array('status'=>BolsterLoopholeModel::BOLSTER_LOOPHOLEE_STATUS_ENABLE),$loopholeId);
		}
		
		
	}
}
?>