<?php
/**
 *
 * 删除科目
 *
 * 20180301
 *
 */
class ReligionDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'religionId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$religionId = $this->argument('religionId');
		
		$groupInfo = $this->service('GeographyReligion')->getReligionInfo($religionId);
		
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		if(!is_array($religionueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('GeographyReligion')->removeReligionId($removeGroupIds);
		
		$sourceTotal = count($religionueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>