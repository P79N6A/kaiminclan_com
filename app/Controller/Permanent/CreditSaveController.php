<?php
/**
 *
 * 授信编辑
 *
 * 20180301
 *
 */
class CreditSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'creditId'=>array('type'=>'digital','tooltip'=>'授信ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'channel_identity'=>array('type'=>'digital','tooltip'=>'渠道'),
			'amount'=>array('type'=>'money','tooltip'=>'额度'),
			'style'=>array('type'=>'digital','tooltip'=>'计息方式'),
			'checkout'=>array('type'=>'digital','tooltip'=>'结账日'),
			'statement'=>array('type'=>'digital','tooltip'=>'账单日'),
            'bank_identity'=>array('type'=>'digital','tooltip'=>'托管银行'),
			'interest'=>array('type'=>'money','tooltip'=>'利息'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$creditId = $this->argument('creditId');
		
		$setarr = array(
			'channel_identity' => $this->argument('channel_identity'),
			'title' => $this->argument('title'),
			'amount' => $this->argument('amount'),
			'style' => $this->argument('style'),
			'checkout' => $this->argument('checkout'),
			'statement' => $this->argument('statement'),
			'bank_identity'=>$this->argument('bank_identity'),
			'interest' => $this->argument('interest'),
			'remark' => $this->argument('remark'),
		);
		
		if($creditId){
			$this->service('PermanentCredit')->update($setarr,$creditId);
		}else{
			
			if($this->service('PermanentCredit')->checkCreditTitle($setarr['title'])){
				
				$this->info('授信已存在',4001);
			}
			
			$this->service('PermanentCredit')->insert($setarr);
		}
	}
}
?>