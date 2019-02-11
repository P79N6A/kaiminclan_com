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
			'code'=>array('type'=>'digital','tooltip'=>'账户识别码'),
			'title'=>array('type'=>'string','tooltip'=>'账户名称'),
			'capital_identity'=>array('type'=>'digital','tooltip'=>'资产'),
			'theater_identity'=>array('type'=>'digital','tooltip'=>'战区'),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'结算货币'),
			'status'=>array('type'=>'digital','tooltip'=>'账户状态'),
			'broker_identity'=>array('type'=>'digital','tooltip'=>'经纪'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$accountId = $this->argument('accountId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'status'=>$this->argument('status'),
			'currency_identity' => $this->argument('currency_identity'),
			'capital_identity' => $this->argument('capital_identity'),
			'theater_identity' => $this->argument('theater_identity'),
			'broker_identity' => $this->argument('broker_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($accountId){
			$this->service('BankrollAccount')->update($setarr,$accountId);
		}else{
			
			$this->service('BankrollAccount')->insert($setarr);
		}
	}
}
?>