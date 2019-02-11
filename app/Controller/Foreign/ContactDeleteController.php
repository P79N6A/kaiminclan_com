<?php
/**
 *
 * 删除合约
 *
 * 20180301
 *
 */
class ContactDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'contactId'=>array('type'=>'digital','tooltip'=>'合约ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$contactId = $this->argument('contactId');
		
		$contactInfo = $this->service('ForeignContact')->getContactInfo($contactId);
		
		if(!$contactInfo){
			$this->info('合约不存在',4101);
		}
		if(!is_array($contactueId)){
			$contactInfo = array($contactInfo);
		}
		
		$removeContactIds = array();
		foreach($contactInfo as $key=>$contact){
				$removeContactIds[] = $contact['identity'];
		}
		
		$this->service('ForeignContact')->removeContactId($removeContactIds);
		
		$sourceTotal = count($contactueId);
		$successNum = count($removeContactIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>