<?php
/**
 *
 * 售后审核
 *
 * 营销
 *
 */
class DrawbackExamineController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'drawbackId'=>array('type'=>'digital','tooltip'=>'申请ID'),
			'refuse'=>array('type'=>'doc','tooltip'=>'拒绝说明','default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'审核结果'),
			'freight'=>array('type'=>'money','tooltip'=>'运费','default'=>0),
			'amount'=>array('type'=>'money','tooltip'=>'金额','default'=>0),
			'integral'=>array('type'=>'money','tooltip'=>'积分','default'=>0),
			'contactId'=>array('type'=>'money','tooltip'=>'地址','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$drawbackId = $this->argument('drawbackId');
		$refuse = $this->argument('refuse');
		$status = $this->argument('status');
		$freight = $this->argument('freight');
		$amount = $this->argument('amount');
		$integral = $this->argument('integral');
		$contact_identity = $this->argument('contact_identity');
		
		$drawbackInfo = $this->service('MarketDrawback')->getdrawbackInfo($drawbackId);
		if(!$drawbackInfo){
			$this->info('申请信息不存在',40011);
		}
		if($drawbackInfo['status'] != MarketDrawbackModel::MARKET_CONTACT_STATUS_WAIT_EXAMINE){
			$this->info('已完成处理',40012);
		}
		
		if(!in_array($status,array(MarketDrawbackModel::MARKET_CONTACT_STATUS_REFUSE,MarketDrawbackModel::MARKET_CONTACT_STATUS_AGREE))){
			$this->info('未定义的审批方式',40013);
		}
		
		if($drawbackInfo['fashion'] == MarketDrawbackModel::MARKET_DRAWBACK_FASHION_PURCHASE && $status == MarketDrawbackModel::MARKET_DRAWBACK_STATUS_AGREE){
			if($contact_identity){
				$this->info('没有提供退货地址',40014);
			}
		}
		
		
		$drawbackData = array(
			'handle_subscriber_identity'=>$this->session('uid'),	
			'refuse'=>$refuse,	
			'status'=>$status,	
			'freight'=>$freight,	
			'amount'=>$amount,	
			'integral'=>$integral,	
			'contact_identity'=>$contact_identity,	
			'handle_time'=>$this->getTime(),	
		);
		
		$this->service('MarketDrawback')->update($drawbackData,$drawbackId);
		
		//缺少退款接口
	}
}
?>