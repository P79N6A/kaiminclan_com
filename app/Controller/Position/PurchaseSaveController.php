<?php
/**
 *
 * 开仓编辑
 *
 * 20180301
 *
 */
class PurchaseSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'purchaseId'=>array('type'=>'digital','tooltip'=>'开仓ID','default'=>0),
			'code'=>array('type'=>'digital','tooltip'=>'识别码'),
			'id'=>array('type'=>'digital','tooltip'=>'品种'),
			'idtype'=>array('type'=>'digital','tooltip'=>'类型'),
			'univalent'=>array('type'=>'money','tooltip'=>'单价'),
			'happen_date'=>array('type'=>'date','tooltip'=>'发生时间','format'=>'dateline','default'=>0),
			'accountId'=>array('type'=>'digital','tooltip'=>'账户','default'=>0),
			'quantity'=>array('type'=>'digital','tooltip'=>'数量'),
            'style'=>array('type'=>'digital','tooltip'=>'类型','default'=>0),
            'magic'=>array('type'=>'digital','tooltip'=>'魔术变量'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$purchaseId = $this->argument('purchaseId');
		$accountId = $this->argument('accountId');
		
		
		$setarr = array(
			'code' => $this->argument('code'),
			'id' => $this->argument('id'),
			'idtype' => $this->argument('idtype'),
            'style' => $this->argument('style'),
			'happen_date' => $this->argument('happen_date'),
			'univalent' => $this->argument('univalent'),
			'quantity' => $this->argument('quantity'),
            'magic' => $this->argument('magic'),
			'remark' => $this->argument('remark')
		);
		
		
		
		if($purchaseId){
			$purchaseData = $this->service('PositionPurchase')->getPurchaseInfo($purchaseId);
			if(!$purchaseData){
				$this->info('采购信息不存在',400006);
			}
		}else{
			if($this->service('PositionPurchase')->checkPurchase($setarr['code'],$setarr['idtype'],$setarr['id'])){
				$this->info('此采购已存在',400004);
			}
		}
		if(!$accountId){
			$accountData = $this->service('BankrollAccount')->getAccountData();
			if(!$accountData){
				$this->info('账户不存在',400001);
			}
			if($accountData['balance'] < 1){
				$this->info('账户可用余额不足',400003);
			}
			$accountId = $accountData['identity'];
		}
		
		$setarr['account_identity'] = $accountId;
		
		if($purchaseId){
			$this->service('PositionPurchase')->update($setarr,$purchaseId);
		}else{
			$purchaseId = $this->service('PositionPurchase')->insert($setarr);
		}
		
		$this->assign('purchaseId',$purchaseId);
	}
}
?>