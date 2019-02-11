<?php
/**
 *
 * 删除分类
 *
 * 20180301
 *
 */
class NationalityDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'nationalityId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$nationalityId = $this->argument('nationalityId');
		
		$groupInfo = $this->service('GeographyNationality')->getNationalityInfo($nationalityId);
		
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		if(!is_array($nationalityueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('GeographyNationality')->removeNationalityId($removeGroupIds);
		
		$sourceTotal = count($nationalityueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>