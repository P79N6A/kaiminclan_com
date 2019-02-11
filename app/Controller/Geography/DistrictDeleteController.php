<?php
/**
 *
 * 删除地区
 *
 * 20180301
 *
 */
class DistrictDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'districtId'=>array('type'=>'digital','tooltip'=>'地区ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$districtId = $this->argument('districtId');
		
		$groupInfo = $this->service('GeographyDistrict')->getDistrictInfo($districtId);
		
		if(!$groupInfo){
			$this->info('地区不存在',4101);
		}
		if(!is_array($districtueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
				$removeGroupIds[] = $group['identity'];
		}
		
		$this->service('GeographyDistrict')->removeDistrictId($removeGroupIds);
		
		$sourceTotal = count($districtueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>