<?php
/**
 *
 * 分类启用
 *
 * 20180301
 *
 */
class FlatlandsEnableController extends Controller {
	
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
		
		if($groupInfo['status'] == GeographyFlatlandsModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('GeographyFlatlands')->update(array('status'=>GeographyFlatlandsModel::PAGINATION_BLOCK_STATUS_ENABLE),$flatlandsId);
		}
		
		
	}
}
?>