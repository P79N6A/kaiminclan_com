<?php
/**
 *
 * 删除客户等级
 *
 * 20180301
 *
 */
class TheaterDeleteController extends Controller {
	
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
		if(!is_array($theaterId)){
			$theaterInfo = array($theaterInfo);
		}
		
		$removeGroupIds = array();
		foreach($theaterInfo as $key=>$theater){
				$removeGroupIds[] = $theater['identity'];
		}
		
		$this->service('PropertyTheater')->removeTheaterId($removeGroupIds);
		
		$sourceTotal = count($theaterId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>