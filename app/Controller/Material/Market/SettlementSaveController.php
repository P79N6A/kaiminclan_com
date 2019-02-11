<?php
/**
 *
 * 结算编辑
 *
 * 营销
 *
 */
class SettlementSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'settlementId'=>array('type'=>'digital','tooltip'=>'结算ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
			'appkey'=>array('type'=>'string','tooltip'=>'APPKEY'),
			'secret'=>array('type'=>'string','tooltip'=>'SECRET'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$settlementId = $this->argument('settlementId');
		$settlementInsertData = array(
			'title'=>$this->argument('title'),
			'remark'=>$this->argument('remark'),
			'appkey'=>$this->argument('appkey'),
			'secret'=>$this->argument('secret'),
		);
		
		if($settlementId){
			$settlementData = $this->service('MarketSettlement')->getSettlementInfo($settlementId);
			if(!$settlementData){
				$this->info('结算方式不存在',40002);
			}			
		}else{
			if($this->service('MarketSettlement')->checkSettlementName($settlementInsertData['title'])){
				$this->info('此结算方式已存在',40001);
			}
		}
		
		
		if($settlementId){
			$this->service('MarketSettlement')->update($settlementInsertData,$settlementId);
		}else{
			$this->service('MarketSettlement')->insert($settlementInsertData);
		}
		
		
	}
}
?>