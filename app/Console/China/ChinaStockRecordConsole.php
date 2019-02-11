<?php
/**
 *
 * 证券
 *
 * 中国区
 *
 * 
 * http://vip.stock.finance.sina.com.cn/corp/go.php/vCI_CorpInfo/stockid/600903.phtml
 *
 */
error_reporting(0);
date_default_timezone_set('Asia/shanghai');
class ChinaStockRecordConsole extends Console {
	private $db;
	
	private $database = array();
	private $baseFolder = './';
	
	public function __construct(){
		$conn = mysql_connect('47.93.184.56:3389','bamboo','123456') or die(mysql_error());
		mysql_select_db('intelligence',$conn);
		mysql_query('set names utf8',$conn);
		
		$this->db = $conn;
	}
	
	public function fire(){
		
		list(,$start,$perpage) = $_SERVER['argv'];
		
		$start = max(1,intval($start));
		$perpage = max(100,intval($perpage));
		
		$listdata = $this->fetchAll('pre_securities_stock','record = 1',$start,$perpage,array('identity','symbol'));
		
		
		foreach($listdata as $key=>$stockData){
			$stockData['symbol'] = substr(strval($stockData['symbol']+1000000),1,7);
			$url = 'http://vip.stock.finance.sina.com.cn/corp/go.php/vCI_CorpInfo/stockid/'.$stockData['symbol'].'.phtml';
			$result = $this->curl($url);
			
			$content = explode('<table id="comInfo1" width="100%">',$result);
			$content = explode('<div class="footer">',$content[1]);
			
			preg_match_all('/<td(.*)class="ccl">(.*)<\/td>/i',$content[0],$matches);
			if(isset($matches[2])){
				switch(count($matches[2])){
					case 6:
						list($fullname,$en,,$regAdd,$offAdd,$business) = $matches[2];
					break;
					case 7:
						list($fullname,$en,,$regAdd,$offAdd,,$business) = $matches[2];
					break;
					
				}
				echo $fullname."\r\n";
				$sql = 'UPDATE pre_securities_stock SET `fullname` = "'.mb_convert_encoding($fullname,'utf8','gbk').'",
				`english` = "'.$en.'",
				`registered_address` = "'.mb_convert_encoding($regAdd,'utf8','gbk').'",
				`office_address` = "'.mb_convert_encoding($offAdd,'utf8','gbk').'",
				`business` = "'.addslashes(mb_convert_encoding($business,'utf8','gbk')).'",record = 0
				WHERE identity = '.$stockData['identity'];
				
				mysql_query($sql,$this->db);
				if(mysql_error()){
					file_put_contents('F:\data\wwwbamboo\information\intelligence\data\china_record.log',$sql."\r\n".mysql_error()."\r\n",FILE_APPEND);
				}
			}else{
				var_dump($matches[2]);
				$this->output('没有数据');
			}
			$range = array(30,45,55,25,60);
			sleep($range[mt_rand(0,4)]);
		}
	}
	
	
	private function output($str){
		echo mb_convert_encoding($str,'gbk','utf8')."\r\n";
	}
	
	
	private function curl($url){
		 $ch = curl_init();
		 
		 $ip = mt_rand(11, 191).".".mt_rand(0, 240).".".mt_rand(1, 240).".".mt_rand(1, 240);   //随机ip 
		 $agent = array_values($this->agentarry);
		 $useragent = $agent[mt_rand(0,count($agent))]; 
		 
		// var_dump($ip,$useragent); die();
		 
		 $header = array( 
			'CLIENT-IP:'.$ip, 
			'X-FORWARDED-FOR:'.$ip, 
		 ); 
		 
		 // 2. 设置选项，包括URL
		 curl_setopt($ch,CURLOPT_URL,$url);
		 curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		// curl_setopt($ch,CURLOPT_HEADER,0);
		 curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
		 curl_setopt($ch, CURLOPT_USERAGENT, $useragent); //模拟常用浏览器的useragent  
		 // 3. 执行并获取HTML文档内容
		 $output = curl_exec($ch);
		 if($output === FALSE ){
			echo "CURL Error:".curl_error($ch);
		 }
		 curl_close($ch);
		 
		 return $output;
	}
	
	private function insert($table,$data = array()){
		$field = '*';
		if($data){
			if(is_array($data)){
				$field = '`'.implode('`,`',$data).'`';
			}else{
				$field = $data;
			}
		}
		
		$output = array();
		$result = mysql_query('SELECT '.$field.' FROM pre_securities_concept WHERE '.$where,$this->db);
		if(mysql_num_rows($result)){
			$output = mysql_fetch_array($result);
		}
		return $output;
	}
	
	private function fetchRow($table,$where,$data = ''){
		$field = '*';
		if($data){
			if(is_array($data)){
				$field = '`'.implode('`,`',$data).'`';
			}else{
				$field = '`'.$data.'`';
			}
		}
		
		$output = array();
		$result = mysql_query('SELECT '.$field.' FROM pre_securities_concept WHERE '.$where,$this->db);
		if(mysql_num_rows($result)){
			$output = mysql_fetch_array($result);
		}
		return $output;
	}
	
	private function fetchAll($table,$where,$start = 0,$offset = 0,$data = ''){
		$field = '*';
		if($data){
			if(is_array($data)){
				$field = '`'.implode('`,`',$data).'`';
			}else{
				$field = '`'.$data.'`';
			}
		}
		
		if($offset){
			$start = max(0,($start-1)*$offset);
			$limit = $start.','.$offset;
		}
		
		
		$sql = 'SELECT '.$field.' FROM '.$table.($where ? ' WHERE '.$where:'').($limit? ' LIMIT '.$limit:'');
		$output = array();
		$result = mysql_query($sql,$this->db) or die(mysql_error());
		
		while($row = mysql_fetch_array($result)){
			$output[] = $row;
		}
		
		return $output;
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
	
	protected $agentarry=array( 
    //PC端的UserAgent  
    "safari 5.1 – MAC"=>"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",  
    "safari 5.1 – Windows"=>"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50",  
    "Firefox 38esr"=>"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0",  
    "IE 11"=>"Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.30729; .NET CLR 3.5.30729; InfoPath.3; rv:11.0) like Gecko",  
    "IE 9.0"=>"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0",  
    "IE 8.0"=>"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)",  
    "IE 7.0"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)",  
    "IE 6.0"=>"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",  
    "Firefox 4.0.1 – MAC"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",  
    "Firefox 4.0.1 – Windows"=>"Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",  
    "Opera 11.11 – MAC"=>"Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11",  
    "Opera 11.11 – Windows"=>"Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11",  
    "Chrome 17.0 – MAC"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",  
    "傲游（Maxthon）"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)",  
    "腾讯TT"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0)",  
    "世界之窗（The World） 2.x"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",  
    "世界之窗（The World） 3.x"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; The World)",  
    "360浏览器"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)",  
    "搜狗浏览器 1.x"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SE 2.X MetaSr 1.0; SE 2.X MetaSr 1.0; .NET CLR 2.0.50727; SE 2.X MetaSr 1.0)",  
    "Avant"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser)",  
    "Green Browser"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",  
    //移动端口  
    "safari iOS 4.33 – iPhone"=>"Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",  
    "safari iOS 4.33 – iPod Touch"=>"Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",  
    "safari iOS 4.33 – iPad"=>"Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",  
    "Android N1"=>"Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",  
    "Android QQ浏览器 For android"=>"MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",  
    "Android Opera Mobile"=>"Opera/9.80 (Android 2.3.4; Linux; Opera Mobi/build-1107180945; U; en-GB) Presto/2.8.149 Version/11.10",  
    "Android Pad Moto Xoom"=>"Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13",  
    "BlackBerry"=>"Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+",  
    "WebOS HP Touchpad"=>"Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.70 Safari/534.6 TouchPad/1.0",  
    "UC标准"=>"NOKIA5700/ UCWEB7.0.2.37/28/999",  
    "UCOpenwave"=>"Openwave/ UCWEB7.0.2.37/28/999",  
    "UC Opera"=>"Mozilla/4.0 (compatible; MSIE 6.0; ) Opera/UCWEB7.0.2.37/28/999",  
    "微信内置浏览器"=>"Mozilla/5.0 (Linux; Android 6.0; 1503-M02 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.2 TBS/036558 Safari/537.36 MicroMessenger/6.3.25.861 NetType/WIFI Language/zh_CN",  
   // ""=>"",  
  
);  
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


$o = new ChinaStockRecordConsole();
$o ->fire();