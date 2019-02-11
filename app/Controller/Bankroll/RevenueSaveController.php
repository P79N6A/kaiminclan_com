<?php
/**
 *
 * 转入编辑
 *
 * 20180301
 *
 */
class RevenueSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'revenueId'=>array('type'=>'digital','tooltip'=>'转入ID','default'=>0),
			'account_identity'=>array('type'=>'digital','tooltip'=>'账户'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
			'amount'=>array('type'=>'money','tooltip'=>'金额','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$revenueId = $this->argument('revenueId');
		
		$setarr = array(
			'amount' => $this->argument('amount'),
			'account_identity' => $this->argument('account_identity'),
			'subject_identity' => $this->argument('subject_identity'),
			'remark' => $this->argument('remark')
		);
		
		
		if($revenueId){
			$this->service('BankrollRevenue')->update($setarr,$revenueId);
		}else{
			
			$result = $this->service('BankrollRevenue')->insert($setarr);
			if(!$result){
				$this->info('存款失败',400001);
			}
		}
	}
}
?>