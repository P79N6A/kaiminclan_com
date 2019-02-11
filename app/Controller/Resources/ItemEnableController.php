<?php
/**
 *
 * 条目启用
 *
 * 20180301
 *
 */
class ItemEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'itemId'=>array('type'=>'digital','tooltip'=>'条目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$itemId = $this->argument('itemId');
		
		$groupInfo = $this->service('PaginationItem')->getItemInfo($itemId);
		if(!$groupInfo){
			$this->info('条目不存在',4101);
		}
		
		if($groupInfo['status'] == PaginationItemModel::PAGINATION_ITEM_STATUS_DISABLED){
			$this->service('PaginationItem')->update(array('status'=>PaginationItemModel::PAGINATION_ITEM_STATUS_ENABLE),$itemId);
		}
		
		
	}
}
?>