<?php
/**
 *
 * 账户编辑
 *
 * 20180301
 *
 */
class AccountSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'accountId'=>array('type'=>'digital','tooltip'=>'账户ID','default'=>0),
			'typological_identity'=>array('type'=>'digital','tooltip'=>'账户类型'),
			'bank_identity'=>array('type'=>'digital','tooltip'=>'托管银行'),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'code'=>array('type'=>'string','tooltip'=>'号码','length'=>80),
			'amount'=>array('type'=>'money','tooltip'=>'余额'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$accountId = $this->argument('accountId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'code' => $this->argument('code'),
			'amount' => $this->argument('amount'),
			'typological_identity' => $this->argument('typological_identity'),
			'bank_identity' => $this->argument('bank_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($accountId){
			$this->service('MechanismAccount')->update($setarr,$accountId);
		}else{
			
			if($this->service('MechanismAccount')->checkAccountTitle($setarr['title'],$setarr['account_identity'])){
				
				$this->info('账户已存在',4001);
			}
			
			$this->service('MechanismAccount')->insert($setarr);
		}
	}
}
?>