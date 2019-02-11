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
class QuotationChinaFuturesDailyConsole extends Console {
	private $debug=1;
	private $apiUrl = 'http://stock2.finance.sina.com.cn/futures/api/json.php/IndexService.getInnerFuturesDailyKLine';
	
	public function fire(){
		list(,,$start) = $_SERVER['argv'];
		$start = intval($start);
		$start = $start < 1 ? 1:$start;
		$perpage = 300;

		if(!$this->debug){
			$week = date('w');
			if(in_array($week,array(0,6))){
				$this->info('周末');exit();
			}
			$d = date('md');
			if(in_array($d,array('0617','0618','0616','0922','0923','0924','101','102','103','104','105','106','107'))){
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
		}
		
		$this->info($start);
		$where = array('status'=>0);
		
		$listdata = $this->service('MaterialProduct')->getProductList($where,$start,$perpage,'identity desc');
		if($listdata['total'] < 1){
			$this->info('没有数据12');
			exit();
		}
		if($start > ceil($listdata['total']/$perpage)){
			$this->info('exit');
			exit();
		}
		foreach($listdata['list'] as $key=>$stock){
			$stock['code'] = strtoupper(trim($stock['code']));
			
			$symbol = $stock['code'];
			
			$this->info($symbol);
			$symbolData[$symbol] = $stock['identity'];
				
			$url = str_replace('{_symbol_}',($symbol),$this->apiUrl);
			$temp = array();
			$param = array(
				'symbol'=>$symbol.'0',
			);
			
			$sourceText = $this->helper('curl')->init($url)->data($param)->fetch();
			
		
			if(empty($sourceText) || $sourceText == null){
				$this->info("no data");
				continue;
			}
			$list = json_decode($sourceText,true);
			foreach($list as $cnt=>$data){
				
				list($curTime,$open,$high,$low,$close,$valume) = $data;
					$this->info($curTime);
				$curTime = strtotime($curTime);
				
				$stockId = $stock['identity'];
				$cycle = $curTime;
				
				$checkExists = $this->checkSymbol($stockId,$cycle);
				if($checkExists){
					$this->info("1已更新");
					continue;
				}
				
				
				/*
				$oscillatorData = $this->service('QuotationOscillator')->data(
					$stock['identity'],
					date('YmdHi',($curTime)),
					array('fast'=>36,'slow'=>16,'signal'=>10),
					array('open'=>$open,'close'=>$close,'low'=>$low,'high'=>$high)
				)->get();
				
				
				$powerData = $this->service('QuotationDirection')->data(
					$stock['identity'],
					date('YmdHi',($curTime)),
					array('ema'=>480,'wma'=>960),
					array('close'=>$close)
				)->get();
				*/
				$setarr = array(
					'id'=>$stock['identity'],
					'cycle'=>($curTime),
					'open'=>$open,
					'low'=>$low,
					'high'=>$high,
					'close'=>$close,
					'amount'=>$valume,
					'valume'=>$valume
				);
				
				$table = 'china_futures_'.date('Y',$cycle);
				
				$this->model('QuotationMarket')->subtable($table)->data($setarr)->add();
			}
		}			
	}
	
	private function checkSymbol($stockId,$cycle){
		
		$output = array();
		$db = $this->getDb('futures',$stockId);
		
		$table = 'china_futures_'.date('Y',$cycle);
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