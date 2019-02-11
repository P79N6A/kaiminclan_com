<?php
/**
 *
 * 调账编辑
 *
 * 20180301
 *
 */
class AdjustmentSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'adjustmentId'=>array('type'=>'digital','tooltip'=>'调账ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'情况说明'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'first_subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
            'second_subject_identity'=>array('type'=>'digital','tooltip'=>'科目','default'=>0),
            'third_subject_identity'=>array('type'=>'digital','tooltip'=>'科目','default'=>0),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'货币'),
			'bank_identity'=>array('type'=>'digital','tooltip'=>'渠道'),
			'into_account_identity'=>array('type'=>'digital','tooltip'=>'转入账户'),
			'rollout_account_identity'=>array('type'=>'digital','tooltip'=>'转出账户'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$adjustmentId = $this->argument('adjustmentId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'amount' => $this->argument('amount'),
			'first_subject_identity' => $this->argument('first_subject_identity'),
            'second_subject_identity' => $this->argument('second_subject_identity'),
            'third_subject_identity' => $this->argument('third_subject_identity'),
			'currency_identity' => $this->argument('currency_identity'),
			'into_account_identity' => $this->argument('into_account_identity'),
			'bank_identity' => $this->argument('bank_identity'),
			'rollout_account_identity' => $this->argument('rollout_account_identity'),
			'remark' => $this->argument('remark')
		);

		if($setarr['into_account_identity'] == $setarr['rollout_account_identity']){
		    $this->info('转入转出账户不许一致',2001);

        }
		
		if($adjustmentId){
			$this->service('DealingsAdjustment')->update($setarr,$adjustmentId);
		}else{

            if($this->service('DealingsAdjustment')->checkAdjustmentTitle($setarr['title'])){

                $this->info('调账已存在',4001);
            }

            $this->service('DealingsAdjustment')->insert($setarr);
		}
	}
}
?>