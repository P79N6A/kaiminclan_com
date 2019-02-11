<?php
/**
 *
 * 客户启用
 *
 * 20180301
 *
 */
class ScaleEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'scaleId'=>array('type'=>'digital','tooltip'=>'客户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$scaleId = $this->argument('scaleId');
		
		$scaleInfo = $this->service('PropertyScale')->getScaleInfo($scaleId);
		if(!$scaleInfo){
			$this->info('客户不存在',4101);
		}
		
		if($scaleInfo['status'] == PropertyScaleModel::BILLBOARD_CATALOGUE_STATUS_DISABLED){
			$this->service('PropertyScale')->update(array('status'=>PropertyScaleModel::BILLBOARD_CATALOGUE_STATUS_ENABLE),$scaleId);
		}
		
		
	}
}
?>