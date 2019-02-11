<?php
/**
 *
 * 删除维护
 *
 * 20180301
 *
 */
class SoftwareDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'softwareId'=>array('type'=>'digital','tooltip'=>'维护ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$softwareId = $this->argument('softwareId');
		
		$softwareInfo = $this->service('BolsterSoftware')->getSoftwareInfo($softwareId);
		
		if(!$softwareInfo){
			$this->info('维护不存在',4101);
		}
		if(!is_array($softwareueId)){
			$softwareInfo = array($softwareInfo);
		}
		
		$removeSoftwareIds = array();
		foreach($softwareInfo as $key=>$software){
			if($software['attachment_num'] < 1){
				$removeSoftwareIds[] = $software['identity'];
			}
		}
		
		$this->service('BolsterSoftware')->removeSoftwareId($removeSoftwareIds);
		
		$sourceTotal = count($softwareueId);
		$successNum = count($removeSoftwareIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>