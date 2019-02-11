<?php
/**
 *
 * 禁用布局
 *
 * 20180301
 *
 */
class LayoutDisableController extends Controller {
	
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
		
		if($groupInfo['status'] == TemplateLayoutModel::PAGINATION_LAYOUT_STATUS_ENABLE){
			$this->service('TemplateLayout')->update(array('status'=>TemplateLayoutModel::PAGINATION_LAYOUT_STATUS_DISABLED),$layoutId);
		}
	}
}
?>