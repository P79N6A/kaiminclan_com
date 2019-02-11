<?php
/**
借此提醒自己
这个世界上的事情，有无限可能。只要我们去深究，可以推导出N种套路。
可是，具体我们自己而言，时间却是有限；
2018已经过去，失去的，得到的；已成过往，留下的只是那些不可描述的东西。

这里的脚本，本质只是解决输入端。基本的路线无须调整。
以此为基准，征战世界吧。
不应把自己局限在这500行代码里面。

SELECT DATE_FORMAT(FROM_UNIXTIME(cycle),"%Y-%m-%d %H:%i") FROM pre_quotation_china_stock_minute_2019_05 WHERE id= 10992 AND cycle > UNIX_TIMESTAMP(CURDATE());
*/
class QuotationChinaStockMinuteConsole extends Console {
	private $apiUrl = 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData';
	
	public function fire(){
		list(,,$start) = $_SERVER['argv'];
		$start = intval($start);
		$start = $start < 1 ? 1:$start;
		$perpage = 300;

		$week = date('w');
		if(in_array($week,array(0,6))){
			$this->info('周末');exit();
		}
		$d = date('md');
		if(in_array($d,array('0617','0618','0616','0922','0923','0924','101','102','103','104','105','106','107','0204','0205','0206','0207','0208'))){
			$this->info('假日');exit();
		}
		$curHour = date('Hi');
		if(substr($curHour,0,1) == 0){
			$curHour = substr($curHour,1);
		}
		if($curHour < 900){
			$this->info('未开盘');exit();
		}
		if($curHour > 1530){
			$this->info('收盘');exit();
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
			$param = array(
				'symbol'=>$symbol,
				'scale'=>15,
				'ma'=>5,
				'datalen'=>4
			);
			
			if(date('Hi') > 1500){
				$param['datalen'] = 16;
			}
			
			$sourceText = $this->helper('curl')->init($url)->data($param)->fetch();
			
		
			if(empty($sourceText) || $sourceText == null || strpos($sourceText,'},{') === false){
				$this->info("no data");
				continue;
			}
			$sourceText = substr($sourceText,2,-2);
			$list = explode('},{',$sourceText);
			foreach($list as $cnt=>$text){
				
				$tempData = explode(',',$text);
				$curTime = strtotime(substr($tempData[0],5,-1));
				$open = substr($tempData[1],6,-1);
				$high = substr($tempData[2],6,-1);
				$low = substr($tempData[3],5,-1);
				$close = substr($tempData[4],7,-1);
				
				$stockId = $stock['identity'];
				$cycle = $curTime;
				
				$checkExists = $this->checkSymbol($stockId,$cycle);
				if($checkExists){
					$this->info("1已更新");
					continue;
				}
				
				
				
				$oscillatorData = $this->service('QuotationOscillator')->data(
					$stock['identity'],
					date('YmdHi',($curTime)),
					array('fast'=>72,'slow'=>32,'signal'=>20),
					array('open'=>$open,'close'=>$close,'low'=>$low,'high'=>$high)
				)->get();
				
				
				$powerData = $this->service('QuotationDirection')->data(
					$stock['identity'],
					date('YmdHi',($curTime)),
					array('ema'=>480,'wma'=>960),
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
				
				$table = 'china_stock_minute_'.date('Y_W');
				
				$this->model('QuotationMarket')->subtable($table)->data($setarr)->add();
			}
		}			
	}
	
	private function checkSymbol($stockId,$cycle){
		
		$output = array();
		$db = $this->getDb('stock',$stockId);
		
		$table = 'stock_minute_'.date('Y_W');
		$sql ='
			  CREATE TABLE IF NOT EXISTS '.$table.'
			  (
			  identity INTEGER PRIMARY KEY     autoincrement,
			  stock_identity           INT    NOT NULL,
			  cycle            INT     NOT NULL);
		';
		$res = $db->exec($sql);
		if(!$res){
			var_dump($db->lastErrorMsg());
		}
		$sql = 'SELECT identity FROM '.$table.'  WHERE stock_identity ='.$stockId.' AND cycle = '.$cycle.';';
		
		$result = $db ->query($sql);
		if(!$result){
			var_dump($db->lastErrorMsg());
		}
   
		while($row = $result->fetchArray(SQLITE3_ASSOC)){
			$output[] = $row;
		}
		
		if(count($output) < 1){
			$db->exec('INSERT INTO '.$table.'(stock_identity,cycle) VALUES ('.$stockId.','.$cycle.')');
		}
		
		return count($output);
		
	}
	
	private function getDb($database,$table){
		if(!$database || !$table){
			$this->info('未定义数据库');
		}
		$folder = __STORAGE__.'/sqlite/'.$database;
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}		
		$database = $folder.'/'.$table.'.db';
		return new SQLite3($database);
	}
	
	
}