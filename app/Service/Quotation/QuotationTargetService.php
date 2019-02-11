<?php
class QuotationTargetService extends Service {
	const QUOTATION_TARGET_CYCLE_DAILY = 1440;
	const QUOTATION_TARGET_CYCLE_HOURLY = 240;
	const QUOTATION_TARGET_CYCLE_MINUTE = 60;
	
	const QUOTATION_TARGET_DIECTION_SELL = 2;
	const QUOTATION_TARGET_DIECTION_BUY = 1;
	
	const QUOTATION_TARGET_IDTYPE_STOCK = 1;
	
	
	public function pushStockDailyBuyByStockId($stockId){
		
		$targetData = array(
			'idtype'=>self::QUOTATION_TARGET_IDTYPE_STOCK,
			'id'=>$stockId,
			'direction'=>self::QUOTATION_TARGET_DIECTION_BUY,
			'cycle'=>self::QUOTATION_TARGET_CYCLE_DAILY
		);
		$this->insert($targetData, 'china_stock_'.date('Y'));
		
	}
	public function pushStockDailySellByStockId($stockId){
		
		$targetData = array(
			'idtype'=>self::QUOTATION_TARGET_IDTYPE_STOCK,
			'id'=>$stockId,
			'direction'=>self::QUOTATION_TARGET_DIECTION_SELL,
			'cycle'=>self::QUOTATION_TARGET_CYCLE_DAILY
		);
		$this->insert($targetData, 'china_stock_'.date('Y'));
	
	}
	public function pushStockHourlyBuyByStockId($stockId){
		
		$targetData = array(
			'idtype'=>self::QUOTATION_TARGET_IDTYPE_STOCK,
			'id'=>$stockId,
			'direction'=>self::QUOTATION_TARGET_DIECTION_BUY,
			'cycle'=>self::QUOTATION_TARGET_CYCLE_HOURLY
		);
		$this->insert($targetData, 'china_stock_hourly_'.date('Y_m'));
		
	}
	public function pushStockHourlySellByStockId($stockId){
		
		$targetData = array(
			'idtype'=>self::QUOTATION_TARGET_IDTYPE_STOCK,
			'id'=>$stockId,
			'direction'=>self::QUOTATION_TARGET_DIECTION_SELL,
			'cycle'=>self::QUOTATION_TARGET_CYCLE_HOURLY
		);
		$this->insert($targetData, 'china_stock_hourly_'.date('Y_m'));
	
	}
	public function pushStockMinuteBuyByStockId($stockId){
		
		$targetData = array(
			'idtype'=>self::QUOTATION_TARGET_IDTYPE_STOCK,
			'id'=>$stockId,
			'direction'=>self::QUOTATION_TARGET_DIECTION_BUY,
			'cycle'=>self::QUOTATION_TARGET_CYCLE_MINUTE
		);
		$this->insert($targetData,'china_stock_minute_'.date('Y_W'));
	
	}
	public function pushStockMinuteSellByStockId($stockId){
		
		$targetData = array(
			'idtype'=>self::QUOTATION_TARGET_IDTYPE_STOCK,
			'id'=>$stockId,
			'direction'=>self::QUOTATION_TARGET_DIECTION_SELL,
			'cycle'=>self::QUOTATION_TARGET_CYCLE_MINUTE
		);
		$this->insert($targetData, 'china_stock_minute_'.date('Y_W'));
	}
	
	public function insert($targetData,$table){
		$targetData['dateline'] = $this->getTime();
		$this->model('QuotationChance')->subtable($table)->data($targetData)->add();
	}
}