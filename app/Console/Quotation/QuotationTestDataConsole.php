<?php
/**

*/
class QuotationTestDataConsole extends Console {
	private $apiUrl = 'http://money.finance.sina.com.cn/quotes_service/api/json_v2.php/CN_MarketData.getKLineData';
	
	public function fire(){
		
		$symbol = 'sz000001';
		
		$key = 'texst_qu_data_240_'.$symbol;
		
		$sourceText = $this->cache($key);
		if(!$sourceText){
			$url = str_replace('{_symbol_}',$symbol,$this->apiUrl);
			$param = array(
				'symbol'=>$symbol,
				'scale'=>5,
				'ma'=>5,
				'datalen'=>240
			);
			$sourceText = $this->helper('curl')->init($url)->data($param)->fetch();
			if(!$sourceText){
				$this->info('数据获取失败');exit();
			}
			$sourceText = substr($sourceText,2,-2);
			$this->cache($key,$sourceText);
			
		}
		
		
		$list = explode('},{',$sourceText);
		foreach($list as $cnt=>$text){			
			$tempData = explode(',',$text);
			$curTime = strtotime(substr($tempData[0],5,-1));
			$open = substr($tempData[1],6,-1);
			$high = substr($tempData[2],6,-1);
			$low = substr($tempData[3],5,-1);
			$close = substr($tempData[4],7,-1);
				
				
				
			$oscillatorData = $this->service('QuotationOscillator')->data(
				$stock['identity'],
				date('YmdHi',($curTime)),
				array('fast'=>9,'slow'=>3,'signal'=>3),
				array('open'=>$open,'close'=>$close,'low'=>$low,'high'=>$high)
			)->get();
			
			
			$powerData = $this->service('QuotationDirection')->data(
				$stock['identity'],
				date('YmdHi',($curTime)),
				array('ema'=>240,'wma'=>480),
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
			$this->info($tempData[0].'>>'.$close.'>>'.$oscillatorData['signal']);
		}
	}
	
	public function testSqlite(){
		
				
				$stockId = $stock['identity'];
				$cycle = $curTime;
				
				$checkExists = $this->checkSymbol(1,1440);
				var_dump($checkExists); die();
				if($checkExists){
					continue;
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