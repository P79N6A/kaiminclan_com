<?php
/**
 *
 * 删除模块
 *
 * 20180301
 *
 */
class BlockDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'blockId'=>array('type'=>'digital','tooltip'=>'模块ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$blockId = $this->argument('blockId');
		
		$groupInfo = $this->service('PaginationBlock')->getBlockInfo($blockId);
		
		if(!$groupInfo){
			$this->info('模块不存在',4101);
		}
		if(!is_array($blockueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('PaginationBlock')->removeBlockId($removeGroupIds);
		
		$sourceTotal = count($blockueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>