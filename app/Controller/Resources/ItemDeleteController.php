<?php
/**
 *
 * 删除条目
 *
 * 20180301
 *
 */
class ItemDeleteController extends Controller {
	
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
		
		$itemList = $this->service('PaginationItem')->getItemInfo($itemId);
		
		if(!$itemList){
			$this->info('条目不存在',4101);
		}
		
		$this->service('PaginationItem')->removeItemId($itemId);
		
		
	}
}
?>