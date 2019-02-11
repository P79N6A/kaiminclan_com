<?php
/**
 *
 * 证券
 *
 * 中国区
 *
 * 
 *http://vip.stock.finance.sina.com.cn/corp/go.php/vCI_CorpInfo/stockid/603970.phtml
 *
 */
error_reporting(0);
date_default_timezone_set('Asia/shanghai');
class ChinaStockInitConsole extends Console {
	private $db;
	
	private $database = array();
	private $baseFolder = './';
	
	public function __construct(){
		
		$conn = mysql_connect('47.93.184.56:3389','bamboo','123456') or die(mysql_error());
		mysql_select_db('quotation',$conn);
		mysql_query('set names utf8',$conn);
		
		$result = mysql_query('SHOW TABLES',$conn);
		while ($row=mysql_fetch_array($result))
        {
            $this->database[] = $row[0];
        }
		
		$this->db = $conn;
		
	}
	
	public function fire(){
		
		$result = mysql_query('select identity,id,code from pre_quotation_symbol where idtype = 3 limit 100',$this->db);
		while($symbolData = mysql_fetch_array($result)){
			
			
			//周线
			$this->addQuotation('weekly',$open,$low,$high,$close,$valume,$amount);
			//月线
			$this->addQuotation('monthly',$open,$low,$high,$close,$valume,$amount);
			//季线
			$this->addQuotation('seasonly',$open,$low,$high,$close,$valume,$amount);
			//年线
			$this->addQuotation('yearly',$open,$low,$high,$close,$valume,$amount);
			
		}
	}
	
	
	private function addQuotation($cycle,$open,$low,$high,$close,$valume,$amount){
		
		if(!in_array($cycle,array('daily','weekly','monthly','seasonly','yearly'))){
			return '';
		}

		
		$table = 'pre_quotation_'.$cycle;
		if($cycle == 'daily'){
			$table = $table.'_'.date('Y');
		}
		
		
		$isNewTable = false;
		
		if(!in_array($table,$this->database)){
			mysql_query("
				CREATE TABLE `".$table."` (
					`symbol_identity` INT(11) NOT NULL DEFAULT '0',
					`cycle` INT(11) NOT NULL DEFAULT '0',
					`revolution` BIGINT(20) NOT NULL DEFAULT '0',
					`open` DECIMAL(20,5) NOT NULL DEFAULT '0.00000' COMMENT '开盘价',
					`low` DECIMAL(20,5) NOT NULL DEFAULT '0.00000' COMMENT '最低价',
					`high` DECIMAL(20,5) NOT NULL DEFAULT '0.00000' COMMENT '最高价',
					`close` DECIMAL(20,5) NOT NULL DEFAULT '0.00000' COMMENT '收盘价',
					`valume` DECIMAL(20,5) NOT NULL DEFAULT '0.00000' COMMENT '成交量',
					`amount` DECIMAL(20,5) NOT NULL DEFAULT '0.00000' COMMENT '金额',
					PRIMARY KEY (`symbol_identity`, `cycle`, `revolution`)
				)
				COMMENT='行情'
				COLLATE='utf8_general_ci'
				ENGINE=MyISAM
				ROW_FORMAT=COMPACT
				;
			",$this->db);
			if($msg = mysql_error()){
				var_dump($msg);
				exit();
			}
			$isNewTable = true;
			$this->database[] = $table;
		}
		
		//非日线
		$historyQuotation = array();
		if(!$isNewTable && !in_array($cycle,array('weekly','monthly','seasonly','yearly'))){
			$subResult = mysql_query('select * from '.$table.' WHERE symbol_identity = '.$symbolId,$this->db) or die(mysql_error());
			if($subResult && mysql_num_rows($subResult) > 0){
				$historyQuotation = mysql_fetch_array($subResult,MYSQL_ASSOC);
				if($historyQuotation['high'] > $high){
					$high = $historyQuotation['high'];
				}
				if($historyQuotation['low'] < $low){
					$low = $historyQuotation['low'];
				}
				if($historyQuotation['close'] < $low){
					$close = $historyQuotation['close'];
				}
				$valume = $historyQuotation['valume']+$valume;
				$amount = $historyQuotation['amount']+$amount;
			}
		}
		
		switch($cycle){
			case 'daily': $cycle = 1440; break;
			case 'weekly': $cycle = 7200; break;
			case 'monthly': $cycle = 28800; break;
			case 'seasonly': $cycle = 86400; break;
			case 'yearly': $cycle = 345600; break;
		}
		
		
		mysql_query('REPLACE INTO '.$table.' (`symbol_identity`,`cycle`,`revolution`,`open`,`low`,`high`,`close`,`valume`,`amount`) VALUES (
			'.$symbolId.','.strtotime(date('Ymd')).','.$this->getRevolutionTime($cycle,time()).','.$open.','.$low.','.$high.','.$close.','.$valume.','.$amount.'
		)',$this->db);
		
		if($msg = mysql_error()){
			continue;
		}
	}
	
	public function __destruct(){
		if($this->db){
			mysql_close($this->db);
		}
	}
}

class Console {
	
	
	/**
	 * 移动平均
	 * 算法：[M*X+(N-M)*Y']/N
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function ma($ma,$price,$day,$weight){
		return ($weight*$price+($day-$weight)*$ma)/$day;
	}
	/**
	 * 简单移动平均
	 * 算法：(X1+X2+X3+...+Xn)/N
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function sma($sma,$price,$day){
		return (2*$price+($day-1)*$sma)/($day+1);
	}
	/**
	 * 指数平滑移动平均
	 *
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function ema($ema,$price,$day){
		return (2*$price+($day-1)*$ema)/($day+1);
	}
	/**
	 * MACD线
	 *
	 * @param $short 
	 * @param $long 
	 * @param $mid
		DIF:EMA(CLOSE,SHORT)-EMA(CLOSE,LONG);
		DEA:EMA(DIF,MID);
		MACD:(DIF-DEA)*2,COLORSTICK;
	 *
	 */
	private function macd($setting,$quotation,$macd){
		
		list($short,$long,$mid) = $setting;
		list($open,$high,$low,$close,$symbol) = $quotation;
		list($shortEma,$longEma) = $macd;
		
		$dif = $this->ema($shortEma,$close,$short)-$this->ema($longEma,$close,$long);
		$dea = $this->ema($dif,$short);
		$macd = ($dif-$dea)*2;
		return array($dif,$dea,$macd);
	}
	/**
	 * 最大值
	 *
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function hhv($price,$day,$symbol){
		$range = array();
		$filename = $this->baseFolder.'/hhv/'.$symbol.'_'.$day.'.txt';
		$folder = dirname($filename);
		if(!is_dir($folder)){
			mkdir($folder);
		}
		if(is_file($filename)){
			$hhv_data = json_decode(file_get_contents($filename),true);
		}
		if($hhv_data){
			$hhv_data[] = $price;
		}else{
			$hhv_data =  array($price);
		}
		
		$range = $hhv_data;
		if(count($hhv_data) > $day){
			$range = array_slice($hhv_data,1);
		}
		file_put_contents($filename,json_encode($range));
		
		return max($range);
	}
	/**
	 * 最小值
	 *
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function llv($price,$day,$symbol){
		$range = array();
		$filename = $this->baseFolder.'/llv/'.$symbol.'_'.$day.'.txt';
		$folder = dirname($filename);
		if(!is_dir($folder)){
			mkdir($folder);
		}
		if(is_file($filename)){
			$llv_data = json_decode(file_get_contents($filename),true);
		}
		if($llv_data){
			$llv_data[] = $price;
		}else{
			$llv_data =  array($price);
		}
		
		$range = $llv_data;
		if(count($llv_data) > $day){
			$range = array_slice($llv_data,1);
		}
		file_put_contents($filename,json_encode($range));
		
		return min($range);
	}
	/**
	 * KDJ震荡
	 *
	 * @param $setting 
	 * @param $quotation
	 * @param $kdj
	 *
	 */
	private function kdj($setting,$quotation,$kdj){
		
		list($n,$m1,$m2) = $setting;
		
		list($open,$high,$low,$close,$symbol) = $quotation;
		$low = $this->llv($low,$n,$symbol);
		if(is_array($low)){
			list($low) = $low;
		}
		$high = $this->hhv($high,$n,$symbol);
		if(is_array($high)){
			list($high) = $high;
		}
		$rsv = ($close-$low)/($high-$low)*100;	
		
		list($k,$d,$j) = $kdj;
		
		$k = empty($k)?50:$k;
		//$k = 2/3*$k+1/3*$rsv;
		$k = $this->ema($k,$rsv,$m1);
		$k = round($k,6);
		
		$d = empty($d)?50:$d;
		//$d = 2/3*$d+1/3*$k;
		$d = $this->ema($d,$rsv,$m2);
		$d = round($d,6);
		
		$j = 3*$k-2*$d;
		$j = round($j,6);
		
		return array($k,$d,$j);
	}
	
	/**
	 *
	 * 周期
	 *
	 */
	private $revolution = array(
		1,  	//1分钟
		//年    月  日 时  分  秒
		//2017 09  28 14 01  00
		//2017 09  28 14 01  01
		5,  	//5分钟
		//年    月  日 时  分  秒
		//2017 09  28 14 05  00
		//2017 09  28 14 05  05
		//2017 09  28 14 10  05
		//2017 09  28 14 15  05
		15,  	//15分钟
		//年    月  日 时  分  秒
		//2017 09  28 14 15  00
		//2017 09  28 14 15  15
		//2017 09  28 14 30  15
		30,  	//30分钟
		//年    月  日 时  分  秒
		//2017 09  28 14 30  00
		//2017 09  28 14 30  30
		60,  	//60分钟
		//年    月  日 时  分  秒
		//2017 09  28 14 00  00
		//2017 09  28 14 00  60
		1440,	//日
		//年    月  日 时  分  秒
		//2017 09  28 00 00  00
		//2017 09  28 00 14  40
		7200,	//周
		//年    月  日 时  分  秒
		//2017 09  28 00 00  00
		//2017 00  00 00 00  72
		28800,  //月
		//年    月  日 时  分  秒
		//2017 09  00 00 00  00
		//2017 09  00 00 02  88
		86400,  //季
		//年    月  日 时  分  秒
		//2017 00  00 00 00  00
		//2017 00  00 00 08  64
		345600,  //年
		//年    月  日 时  分  秒
		//2017 00  00 00 00  00
		//2017 00  00 00 34  56
	);
	
	/**
	 *
	 * 提取时间线
	 * 根据指定时间，转换数据报表里的时间线
	 * 
	 * @param $cycle 日期
	 * @param $dateline 日期　默认取当天
	 * @return int 
	 */
	 public function getRevolutionTime($cycle,$dateline = 0){
		 $revolution = 0;
		 $currentDateline = time(); 
		 if($dateline){
			 $currentDateline = strtotime($dateline);
		 }
		 switch($cycle){
			case 1: 
				$revolution = date('YmdHi',$currentDateline).'01';
				break;
			case 5: 
				$minute = sprintf('%02d',ceil(date('i',$currentDateline)/5)); 
				$revolution = date('YmdH',$currentDateline).$minute.'05'; 
				break;
			case 15: 
				$minute = sprintf('%02d',ceil(date('i',$currentDateline)/15)); 
				$revolution = date('YmdH',$currentDateline).$minute.'15'; 
				break;
			case 30: 
				$minute = ceil(date('i',$currentDateline)/30); 
				$revolution = date('YmdH',$currentDateline).$minute.'030'; 
				break;
			case 60: 
				$revolution = date('YmdH',$currentDateline).'0060'; 
				break;
			case 1440: 
				$revolution = date('Ymd',$currentDateline).'001440'; 
				break;
			case 7200: 
				$revolution = date('Y',$currentDateline).sprintf('%02d',date('W',$currentDateline)).'00007200'; 
				break;
			case 28800: 
				$revolution = date('Ym',$currentDateline).'00000288'; 
				break;
			case 86400: 
				$season = sprintf('%02d',ceil(date('n',$currentDateline)/3)); 
				$revolution = date('Y',$currentDateline).$season.'00000864';
				break;
			case 345600: 
				$revolution = date('Y',$currentDateline).'0000003456'; 
				break;
		}
		
		return $revolution;
	 }
	
}


$o = new ChinaStockInitConsole();
$o ->fire();