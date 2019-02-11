<?php
/**
 *
 * 删除漏洞
 *
 * 20180301
 *
 */
class LoopholeDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'loopholeId'=>array('type'=>'digital','tooltip'=>'漏洞ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$loopholeId = $this->argument('loopholeId');
		
		$loopholeInfo = $this->service('BolsterLoophole')->getLoopholeInfo($loopholeId);
		
		if(!$loopholeInfo){
			$this->info('漏洞不存在',4101);
		}
		if(!is_array($loopholeueId)){
			$loopholeInfo = array($loopholeInfo);
		}
		
		$removeLoopholeIds = array();
		foreach($loopholeInfo as $key=>$loophole){
				$removeLoopholeIds[] = $loophole['identity'];
		}
		
		$this->service('BolsterLoophole')->removeLoopholeId($removeLoopholeIds);
		
		$sourceTotal = count($loopholeueId);
		$successNum = count($removeLoopholeIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>