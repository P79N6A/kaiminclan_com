<?php
class QuotationChinaForexMinuteConsole extends Console {
	protected $apiUrl = 'http://hq.sinajs.cn/list={_symbol_}';
	
	public function fire(){
		list(,,$start) = $_SERVER['argv'];
		$start = intval($start);
		$start = $start < 1 ? 1:$start;
		$perpage = 300;

		$week = date('w');
		if(in_array($week,array(0,6))){
			//$this->info('周末');exit();
		}
		$d = date('md');
		if(in_array($d,array('0617','0618','0616','0922','0923','0924','101','102','103','104','105','106','107'))){
			$this->info('假日');exit();
		}
		if(date('Hi') < 15){
			$this->info('还没有收盘');exit();
		}
		
		$this->info($start);
		$where = array('exchange_identity'=>1);
		
		$listdata = $this->service('SecuritiesStock')->getStockList($where,$start,$perpage,'identity desc','identity,title,symbol');
		if($listdata['total'] < 1){
			$this->info('没有数据12');
			exit();
		}
		if($start > ceil($listdata['total']/$perpage)){
			$this->info('exit');
			exit();
		}
		foreach($listdata['list'] as $key=>$stock){
			if(is_numeric($symbol)){
				continue;
			}
			$symbol = $stock['symbol'];
			if(substr($symbol,0,1) == 6){
				$symbol = 'sh'.$symbol;
			}else{
				$symbol = 'sz'.$symbol;
			}
			$this->info($symbol);
			$symbolData[$symbol] = $stock['identity'];
				
			$url = str_replace('{_symbol_}',$symbol,$this->apiUrl);
			$temp = array();
			
			
			$sourceText = $this->helper('curl')->init($url)->data(array())->fetch();
			
		
			if(empty($sourceText) || $sourceText == null || strpos($sourceText,'=') === false){
				$this->info("no data");
				continue;
			}
			list(,$sourceText) = explode('=',$sourceText);
			$sourceText = substr($sourceText,1,-1);
			
			$quoationData = explode(',',$sourceText);
			if(count($quoationData) < 2){
				continue;
			}
						
			list(,$open,,$close,$high,$low,,$close,,$traderTotal,$valume) = $quoationData;
			$curDay = date('Y-m-d');
			if($quoationData[30]){
				$curDay = $quoationData[30];
			}
			$curTime = strtotime($curDay);
				
				
			$oscillatorData = $this->service('QuotationOscillator')->data(
				$stock['identity'],
				date('Ymd',($curTime)),
				array('fast'=>18,'slow'=>8,'signal'=>5),
				array('open'=>$open,'close'=>$close,'low'=>$low,'high'=>$high)
			)->get();
			
			
			$powerData = $this->service('QuotationDirection')->data(
				$stock['identity'],
				date('Ymd',($curTime)),
				array('ema'=>120,'wma'=>240),
				array('close'=>$close)
			)->get();
			$setarr = array(
				'id'=>$stock['identity'],
				'cycle'=>($curTime),
				'open'=>$open,
				'low'=>$low,
				'high'=>$high,
				'close'=>$close,
				'amount'=>$close,
				'valume'=>$close,
				'ema'=>$powerData['ema'],
				'wma'=>$powerData['wma'],
				'slow'=>$oscillatorData['slow'],
				'fast'=>$oscillatorData['fast'],
				'signal'=>$oscillatorData['signal'],
			);
			if($powerData['ema'] > $powerData['wma']){
				if($oscillatorData['signal'] < 25){
					$this->service('QuotationTarget')->pushStockDailyBuyByStockId($stock['identity']);
				}
				$setarr['direction'] = 1;
			}
			if($powerData['ema'] < $powerData['wma']){
				if($oscillatorData['signal'] > 80){
					$this->service('QuotationTarget')->pushStockDailySellByStockId($stock['identity']);
				}
				$setarr['direction'] = 2;
			}
			
			
			
			$table = 'forex_minute_'.date('Y_W');
			
			$this->model('QuotationMarket')->subtable($table)->data($setarr)->add();
		}			
	}
	
}