<?php
/**
 *
 * 删除应用
 *
 * 20180301
 *
 */
class InterfaceDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'interfaceId'=>array('type'=>'digital','tooltip'=>'应用ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$interfaceId = $this->argument('interfaceId');
		
		$groupInfo = $this->service('ProgramInterface')->getInterfaceInfo($interfaceId);
		
		if(!$groupInfo){
			$this->info('应用不存在',4101);
		}
		if(!is_array($interfaceueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ProgramInterface')->removeInterfaceId($removeGroupIds);
		
		$sourceTotal = count($interfaceueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>