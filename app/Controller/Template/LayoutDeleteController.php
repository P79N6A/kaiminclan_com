<?php
/**
 *
 * 删除布局
 *
 * 20180301
 *
 */
class LayoutDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'layoutId'=>array('type'=>'digital','tooltip'=>'布局ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$layoutId = $this->argument('layoutId');
		
		$groupInfo = $this->service('TemplateLayout')->getLayoutInfo($layoutId);
		
		if(!$groupInfo){
			$this->info('布局不存在',4101);
		}
		if(!is_array($layoutueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('TemplateLayout')->removeLayoutId($removeGroupIds);
		
		$sourceTotal = count($layoutueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>