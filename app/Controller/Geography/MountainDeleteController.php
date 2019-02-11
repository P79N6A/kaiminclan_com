<?php
/**
 *
 * 删除科目
 *
 * 20180301
 *
 */
class MountainDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'mountainId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$mountainId = $this->argument('mountainId');
		
		$groupInfo = $this->service('GeographyMountain')->getMountainInfo($mountainId);
		
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		if(!is_array($mountainueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('GeographyMountain')->removeMountainId($removeGroupIds);
		
		$sourceTotal = count($mountainueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>