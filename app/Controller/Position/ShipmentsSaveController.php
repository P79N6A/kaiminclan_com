<?php
/**
 *
 * 平仓编辑
 *
 * 20180301
 *
 */
class ShipmentsSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'shipmentsId'=>array('type'=>'digital','tooltip'=>'平仓ID','default'=>0),
			'purchase_identity'=>array('type'=>'digital','tooltip'=>'采购ID'),
			'profit'=>array('type'=>'digital','tooltip'=>'盈利'),
			'univalent'=>array('type'=>'money','tooltip'=>'单价'),
			'happen_date'=>array('type'=>'date','tooltip'=>'发生时间','format'=>'dateline','default'=>0),
			'quantity'=>array('type'=>'digital','tooltip'=>'数量'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$shipmentsId = $this->argument('shipmentsId');
		
		$setarr = array(
			'purchase_identity' => $this->argument('purchase_identity'),
			'profit' => $this->argument('profit'),
			'univalent' => $this->argument('univalent'),
			'happen_date' => $this->argument('happen_date'),
			'quantity' => $this->argument('quantity'),
			'remark' => $this->argument('remark')
		);
		
		
		if($shipmentsId){
			$purchaseData = $this->service('PositionShipments')->getShipmentsInfo($shipmentsId);
			if(!$purchaseData){
				$this->info('平仓信息不存在',400006);
			}
		}else{
			if($this->service('PositionShipments')->checkShipments($setarr['purchase_identity'])){
				$this->info('此平仓已存在',400004);
			}
		}
		
		$purchaseData = $this->service('PositionPurchase')->getPurchaseInfo($setarr['purchase_identity']);
		if(!$purchaseData){
			$this->info('采购信息不存在',400002);
		}
		
		
		$setarr['account_identity'] = $purchaseData['account_identity'];
		
		if($shipmentsId){
			$this->service('PositionShipments')->update($setarr,$shipmentsId);
		}else{
			
			$this->service('PositionShipments')->insert($setarr);
		}
	}
}
?>