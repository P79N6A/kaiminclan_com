<?php
/**
 *
 * 删除分类
 *
 * 20180301
 *
 */
class FlatlandsDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'flatlandsId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$flatlandsId = $this->argument('flatlandsId');
		
		$groupInfo = $this->service('GeographyFlatlands')->getFlatlandsInfo($flatlandsId);
		
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		if(!is_array($flatlandsueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('GeographyFlatlands')->removeFlatlandsId($removeGroupIds);
		
		$sourceTotal = count($flatlandsueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>