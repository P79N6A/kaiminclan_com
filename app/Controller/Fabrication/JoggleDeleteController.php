<?php
/**
 *
 * 删除接口
 *
 * 20180301
 *
 */
class JoggleDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'joggleId'=>array('type'=>'digital','tooltip'=>'接口ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$joggleId = $this->argument('joggleId');
		
		$joggleInfo = $this->service('FabricationJoggle')->getJoggleInfo($joggleId);
		
		if(!$joggleInfo){
			$this->info('接口不存在',4101);
		}
		if(!is_array($joggleueId)){
			$joggleInfo = array($joggleInfo);
		}
		
		$removeJoggleIds = array();
		foreach($joggleInfo as $key=>$joggle){
				$removeJoggleIds[] = $joggle['identity'];
		}
		
		$this->service('FabricationJoggle')->removeJoggleId($removeJoggleIds);
		
		$sourceTotal = count($joggleueId);
		$successNum = count($removeJoggleIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>