<?php
/**
 *
 * 解除渠道锁定
 *
 * 20180301
 *
 */
class AlleywayUnlockController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'alleywayId'=>array('type'=>'digital','tooltip'=>'渠道ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$alleywayId = $this->argument('alleywayId');
		
		$groupInfo = $this->service('PassagewayAlleyway')->getAlleywayInfo($alleywayId);
		if(!$groupInfo){
			$this->info('渠道不存在',4101);
		}
		
		if($groupInfo['status'] == PassagewayAlleywayModel::PASSAGEWAY_ALLEYWAY_STATUS_LOCKED){
			$this->service('PassagewayAlleyway')->update(array('status'=>PassagewayAlleywayModel::PASSAGEWAY_ALLEYWAY_STATUS_ENABLE),$alleywayId);
		}
		
		
	}
}
?>