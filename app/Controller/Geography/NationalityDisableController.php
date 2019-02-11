<?php
/**
 *
 * 禁用分类
 *
 * 20180301
 *
 */
class NationalityDisableController extends Controller {
	
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
		
		if($groupInfo['status'] == GeographyNationalityModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('GeographyNationality')->update(array('status'=>GeographyNationalityModel::PAGINATION_BLOCK_STATUS_DISABLED),$nationalityId);
		}
	}
}
?>