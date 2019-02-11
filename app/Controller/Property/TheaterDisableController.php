<?php
/**
 *
 * 禁用客户等级
 *
 * 20180301
 *
 */
class TheaterDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'theaterId'=>array('type'=>'digital','tooltip'=>'客户等级ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$theaterId = $this->argument('theaterId');
		
		$theaterInfo = $this->service('PropertyTheater')->getTheaterInfo($theaterId);
		if(!$theaterInfo){
			$this->info('客户等级不存在',4101);
		}
		
		if($theaterInfo['status'] == PropertyTheaterModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('PropertyTheater')->update(array('status'=>PropertyTheaterModel::PAGINATION_BLOCK_STATUS_DISABLED),$theaterId);
		}
	}
}
?>