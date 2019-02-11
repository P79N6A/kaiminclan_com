<?php
/**
 *
 * 证券
 *
 * 中国区
 *
 * 新股申购
 *
 *
 */
date_default_timezone_set('Asia/shanghai');
class ChinaStockSinaConsole extends Console {
	
	private $api = 'http://hq.sinajs.cn/list=';
	private $db;
	
	private $database = array();
	
	public function __construct(){
		$this->baseFolder = substr(dirname(__FILE__),0,-12).'/data';
		
		$conn = mysql_connect('47.93.184.56:3389','bamboo','123456') or die('1>>'.mysql_error());
		mysql_select_db('quotation',$conn) or die('2>>'.mysql_error());
		mysql_query('set names utf8',$conn);
		
		$result = mysql_query('SHOW TABLES',$conn) or die('3>>'.mysql_error());
		
		while ($row=mysql_fetch_array($result))
        {
            $this->database[] = $row[0];
        }
		
		$this->db = $conn;
		
	}
	
	public function conn(){
	}
	
	public function fire(){
		
		$result = mysql_query('select identity,code from pre_quotation_symbol where idtype = 3',$this->db);
		while($symbolData = mysql_fetch_array($result)){
			$startCode = substr($symbolData['code'],0,1);
			$code = 'sz'.$symbolData['code'];
			if($startCode == 6){
				$code = 'sh'.$symbolData['code'];
			}
			echo $code."\r\n";
			$urlAddress = $this->api.$code;
			$quoteData = file_get_contents($urlAddress);
			if(!$quoteData){
				continue;
			}
			
			list(,$quoteData) = explode('=',$quoteData);
			list(,$open,,$close,$high,$low,,,$valume,$amount) = explode(',',$quoteData);
			if($open < 1){
				continue;
			}
			
			
			//日线
			$this->addQuotation('daily',$open,$low,$high,$close,$valume,$amount,$symbolData['identity']);
			//月线
			$this->addQuotation('monthly',$open,$low,$high,$close,$valume,$amount,$symbolData['identity']);
			//年线
			$this->addQuotation('yearly',$open,$low,$high,$close,$valume,$amount,$symbolData['identity']);
			
			
		}
	}
	
	/**
	 * 指标
	 */
	private function newSignal($cycle,$revolution,$open,$low,$high,$close,$valume,$amount,$symbolIdentity){
		
		if(!in_array($cycle,array('daily','monthly'))){
			return '';
		}
		
		
		if($cycle == 'daily'){
			$table = 'pre_quotation_shock_'.date('Y');
		}else{
			$table = 'pre_quotation_shock_'.$cycle;
		}
		
		
		
		$isNewTable = false;
		
		if(!in_array($table,$this->database)){
			mysql_query("
				CREATE TABLE `".$table."` (
					`symbol_identity` INT(11) NOT NULL DEFAULT '0',
					`revolution` INT(11) NOT NULL DEFAULT '0',
					`kw` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '周K',
					`dw` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '周D',
					`jw` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '周J',
					`km` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '月K',
					`dm` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '月D',
					`jm` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '月J',
					`kd` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '日K',
					`dd` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '日D',
					`jd` DECIMAL(18,6) NOT NULL DEFAULT '0.000000' COMMENT '日J',
					`dateline` INT(11) NOT NULL DEFAULT '0' COMMENT '时间',
					PRIMARY KEY (`symbol_identity`, `revolution`),
					INDEX `jw` (`jw`),
					INDEX `jm` (`jm`),
					INDEX `jd` (`jd`)
				)
				COMMENT='信号'
				COLLATE='utf8_general_ci'
				ENGINE=MyISAM
				ROW_FORMAT=FIXED
				;
			",$this->db);
			if($msg = mysql_error()){
				var_dump($msg);
				exit();
			}
			$isNewTable = true;
			$this->database[] = $table;
		}
		
		$kdjResult = mysql_query('select * from '.$table.' WHERE symbol_identity = '.$symbolIdentity.' ORDER BY revolution DESC',$this->db);
		$msg = mysql_error();
		if($kdjResult){
			$kdjData = mysql_fetch_array($kdjResult);
			
			if($kdjData){
				
				//短线
				$dkdj = $this->kdj(array(5,2,2),array($open,$high,$low,$close,$symbolIdentity,$cycle),array($kdjData['kd'],$kdjData['dd'],$kdjData['jd']));
				//中线
				$wkdj = $this->kdj(array(18,8,5),array($open,$high,$low,$close,$symbolIdentity,$cycle),array($kdjData['kw'],$kdjData['dw'],$kdjData['jw']));
				//长线
				$mkdj = $this->kdj(array(18*5,8*5,5*5),array($open,$high,$low,$close,$symbolIdentity,$cycle),array($kdjData['km'],$kdjData['dm'],$kdjData['jm']));
				
				$sql = 'REPLACE INTO '.$table.' (`symbol_identity`, `revolution`, `kw`, `dw`, `jw`, `km`, `dm`, `jm`, `kd`, `dd`, `jd`, `dateline`) VALUES (
					'.$symbolIdentity.','.$revolution.','.$wkdj[0].','.$wkdj[1].','.$wkdj[2].','.$mkdj[0].','.$mkdj[1].','.$mkdj[2].','.$dkdj[0].','.$dkdj[1].','.$dkdj[2].','.(time()).'
				)';
				mysql_query($sql,$this->db) or die('4>>'.$sql.mysql_error());
			}
		}
	}
	
	
	private function addQuotation($cycle,$open,$low,$high,$close,$valume,$amount,$symbolId){
		
		
		if(!in_array($cycle,array('daily','monthly'))){
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
		if(!$isNewTable && !in_array($cycle,array('weekly','monthly'))){
			$subResult = mysql_query('select * from '.$table.' WHERE symbol_identity = '.$symbolId,$this->db) or die('5>>'.mysql_error());
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
		
		$cycleLabel = $cycle;
		
		switch($cycle){
			case 'daily': $cycle = 1440; break;
			case 'monthly': $cycle = 28800; break;
		}
		
		
		$week = date('w');
		$today = date('Y-m-d');
		if(in_array($week ,array(6,0))){
			if($week < 1){
				$today = date('Y-m-d',time()-60*60*24*2);
			}else{
				$today = date('Y-m-d',time()-60*60*24);
			}
		}else{
			if(date('H') < 9){
				$today = date('Y-m-d',strtotime('-1 day'));
			}
		}
		
		
		$revolution = $this->getRevolutionTime($cycle,$today);
		mysql_query('REPLACE INTO '.$table.' (`symbol_identity`,`cycle`,`revolution`,`open`,`low`,`high`,`close`,`valume`,`amount`) VALUES (
			'.$symbolId.','.$cycle.','.$revolution.','.$open.','.$low.','.$high.','.$close.','.$valume.','.$amount.'
		)',$this->db) or die('6>>'.mysql_error());
		
		if($msg = mysql_error()){
			continue;
		}
		//计算指标
		$this->newSignal($cycleLabel,$revolution,$open,$low,$high,$close,$valume,$amount,$symbolId);
	}
	
	private function exec_sql($sql){
		if(!mysql_ping($this->db)){
			mysql_close($this->db);		
		
			$conn = mysql_connect('47.93.184.56:3389','bamboo','123456') or die('1>>'.mysql_error());
			mysql_select_db('quotation',$conn) or die('2>>'.mysql_error());
			mysql_query('set names utf8',$conn);
			$this->db = $conn;
		}
		$result = mysql_query($sql,$this->db);
		if($msg = mysql_error()){
			var_dump($msg);
		}
		return $result;
	}
	
	//信号识别
	public function addTrader(){
		/*
		月线
		
		第一梯队
		
		趋势
		10个月EMA大于20个月WMA
		KDJ 取5，2，2组合
		多
		kdj <= 20 
		空
		kdj >= 80
		第二梯队
		KDJ 取（长）90，40，25/（短）18，8，5组合
		长短共振，低于20，多仓；高于80，做空；
		
		日线
		
		第一梯队
		趋势
		10日EMA大于20日WMA
		KDJ 取5，2，2组合
		多
		kdj <= 20 
		空
		kdj >= 80 
		
		第二梯队
		KDJ 取（长）90，40，25/（短）18，8，5组合
		长短共振，低于20，多仓；高于80，做空；
		 */
	}
	
	public function __destruct(){
		if($this->db){
			mysql_close($this->db);
		}
	}
}

class Console {
	protected $baseFolder = './';
	
	
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
	private function hhv($price,$day,$symbol,$cycle){
		$range = array();
		$filename = $this->baseFolder.'/hhv/'.$cycle.'_'.$symbol.'_'.$day.'.txt';
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
	private function llv($price,$day,$symbol,$cycle){
		$range = array();
		$filename = $this->baseFolder.'/llv/'.$cycle.'_'.$symbol.'_'.$day.'.txt';
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
	protected function kdj($setting,$quotation,$kdj){
		
		list($n,$m1,$m2) = $setting;
		
		list($open,$high,$low,$close,$symbol,$cycle) = $quotation;
		$low = $this->llv($low,$n,$symbol,$cycle);
		if(is_array($low)){
			list($low) = $low;
		}
		$high = $this->hhv($high,$n,$symbol,$cycle);
		if(is_array($high)){
			list($high) = $high;
		}
		$rsv = ($close-$low)/($high-$low)*100;	
		$rsv = round($rsv,2);
		list($k,$d,$j) = $kdj;
		
		//$k = empty($k)?50:$k;
		//$k = 2/3*$k+1/3*$rsv;
		$k = empty($k)?1:$k;
		$k = $this->ema($k,$rsv,$m1);
		$k = round($k,2);
		
		//$d = empty($d)?50:$d;
		//$d = 2/3*$d+1/3*$k;
		$d = empty($d)?1:$d;
		$d = $this->ema($d,$k,$m2);
		$d = round($d,2);
		
		$j = 3*$k-2*$d;
		$j = round($j,2);
		
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


$o = new ChinaStockSinaConsole();
$o ->fire();