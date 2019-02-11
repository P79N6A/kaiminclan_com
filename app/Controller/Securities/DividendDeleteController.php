<?php
/**
 *
 * 删除调账
 *
 * 20180301
 *
 */
class DividendDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'dividendId'=>array('type'=>'digital','tooltip'=>'调账ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$dividendId = $this->argument('dividendId');
		
		$groupInfo = $this->service('SecuritiesDividend')->getDividendInfo($dividendId);
		
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		if(!is_array($dividendueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('SecuritiesDividend')->removeDividendId($removeGroupIds);
		
		$sourceTotal = count($dividendueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>