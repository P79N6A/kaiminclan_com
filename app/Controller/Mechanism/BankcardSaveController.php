<?php
/**
 *
 * 银行卡编辑
 *
 * 20180301
 *
 */
class BankcardSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bankcardId'=>array('type'=>'digital','tooltip'=>'银行卡ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'catalogue_identity'=>array('type'=>'digital','tooltip'=>'机构类型','default'=>0),
			'deposit'=>array('type'=>'digital','tooltip'=>'存款利息','default'=>0),
			'loan'=>array('type'=>'digital','tooltip'=>'借款利息','default'=>0),
			'alleyway'=>array('type'=>'digital','tooltip'=>'通道费用','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bankcardId = $this->argument('bankcardId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'deposit' => $this->argument('deposit'),
			'loan' => $this->argument('loan'),
			'alleyway' => $this->argument('alleyway'),
			'remark' => $this->argument('remark')
		);
		
		if($bankcardId){
			$this->service('MechanismBankcard')->update($setarr,$bankcardId);
		}else{
			
			if($this->service('MechanismBankcard')->checkBankcardTitle($setarr['title'])){
				
				$this->info('银行卡已存在',4001);
			}
			
			$this->service('MechanismBankcard')->insert($setarr);
		}
	}
}
?>