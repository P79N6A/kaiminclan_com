<?php
/**
 *
 * 模块启用
 *
 * 20180301
 *
 */
class BlockEnableController extends Controller {
	
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
		
		if($groupInfo['status'] == PaginationBlockModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('PaginationBlock')->update(array('status'=>PaginationBlockModel::PAGINATION_BLOCK_STATUS_ENABLE),$blockId);
		}
		
		
	}
}
?>