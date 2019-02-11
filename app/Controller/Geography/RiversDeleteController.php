<?php
/**
 *
 * 删除分类
 *
 * 20180301
 *
 */
class RiversDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'riversId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$riversId = $this->argument('riversId');
		
		$groupInfo = $this->service('GeographyRivers')->getRiversInfo($riversId);
		
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		if(!is_array($riversueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('GeographyRivers')->removeRiversId($removeGroupIds);
		
		$sourceTotal = count($riversueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>