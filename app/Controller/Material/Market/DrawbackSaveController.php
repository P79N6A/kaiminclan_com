<?php
/**
 *
 * 售后申请
 *
 * 营销
 *
 */
class DrawbackSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'orderddId'=>array('type'=>'digital','tooltip'=>'订单ID'),
			'shoppingId'=>array('type'=>'digital','tooltip'=>'订购ID','default'=>0),
			'attachmentId'=>array('type'=>'digital','tooltip'=>'凭证','default'=>''),
			'reason'=>array('type'=>'doc','tooltip'=>'原因'),
			'amount'=>array('type'=>'money','tooltip'=>'退款金额'),
			'state'=>array('type'=>'digital','tooltip'=>'货物状态'),
			'fashion'=>array('type'=>'digital','tooltip'=>'售后方式','default'=>0),
			'describe'=>array('type'=>'doc','tooltip'=>'情况说明','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$orderddId = $this->argument('orderddId');
		$fashion = $this->argument('fashion');
		$shoppingId = $this->argument('shoppingId');
		$attachment_identity = json_encode($this->argument('attachment_identity'));
		$reason = $this->argument('reason');
		$describe = $this->argument('describe');
		$amount = $this->argument('amount');
		$state = $this->argument('state');
		
		$drawbackData = $this->service('MarketDrawback')->checkDrawback($id,$idtype,$protpery);
		if($drawbackData){
			switch($drawbackData['status']){
				case MarketDrawbackModel::MARKET_DRAWBACK_STATUS_FINISH: $this->info('已经提交过并处理完成',40001); break;
				case MarketDrawbackModel::MARKET_CONTACT_STATUS_HANDLE: $this->info('正在处理中',40002); break;
				case MarketDrawbackModel::MARKET_CONTACT_STATUS_WAIT_EXAMINE: $this->info('等待管理员审核',40003); break;
				case MarketDrawbackModel::MARKET_CONTACT_STATUS_REFUSE: $this->info('您的申请已被拒绝，请勿重复提交',40004); break;
			}
			
		}
		
		
		$drawbackData = array(
			'code'=>$this->service('MarketDrawback')->getDrawbackCode(),	
			'orderdd_identity'=>$orderddId,		
			'fashion'=>$fashion,	
			'amount'=>$amount,	
			'state'=>$state,	
			'shopping_identity'=>$shoppingId,	
			'attachment_identity_text'=>count($attachment_identity) > 0?json_encode($attachment_identity):'',	
			'reason'=>$reason,	
			'describe'=>$describe,	
		);
		
		$this->service('MarketDrawback')->insert($drawbackData);
		
	}
}
?>