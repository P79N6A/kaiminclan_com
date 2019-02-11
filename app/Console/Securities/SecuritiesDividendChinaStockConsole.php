<?php
/**
 *
 * 分红送转
 *
 *  证券板块
 */
class DividendChinaStockConsole extends Console {
	
	protected $intial =0;
	
	protected $api = 'http://vip.stock.finance.sina.com.cn/corp/go.php/vISSUE_ShareBonus/stockid/{_SYMBOL_}.phtml';
	
	
	public function fire(){
		
		
		$start = $_SERVER['argv'][2];
		$start = intval($start) <1 ?1:$start;
		
		$cnt = 0;
		$max = 30;
		
		$where = array(
			'identity'=>array('gt',0),
			'exchange_identity'=>array(1,2)
		);
		
		$count = $this->model('SecuritiesStock')->where($where)->count();
		if(!$count){
			var_dump('no data');
			die();
		}
				
		$perpage = 1000;
		
		$listdata = $this->model('SecuritiesStock')->field('identity,title,symbol,univalent,ipo_date')->where($where)->limit($start,$perpage,$count)->select();
		if(!$listdata){
			var_dump('no data');
			die();
		}
		
		
		
		foreach($listdata as $key=>$stokcData){
			
			$symbol = $stokcData['symbol'];
					
					
			switch(strlen($symbol)){
				case 1:$symbol = '00000'.$symbol; break;
				case 2:$symbol = '0000'.$symbol; break;
				case 3:$symbol = '000'.$symbol; break;
				case 4:$symbol = '00'.$symbol; break;
			}
		
			
			$url = str_replace('{_SYMBOL_}',$symbol,$this->api);
			echo $url."\r\n";
			
			$cacheKey = md5($url);
			$content = $this->cache($cacheKey);
			if(!$content){
				sleep(5);
				$content = $this->loadUrlData($url);
				if(!$content){
					echo 'failed';
				}
				$this->cache($cacheKey,$content);
			}
			

			
			$content = mb_convert_encoding($content,'utf8','gb2312');
		
		
			list(,$resultText) = explode('<table id="sharebonus_1">',$content);
			list($resultText) = explode('<table id="sharebonus_2">',$resultText);
			
			$resultText = $this->helper('Html')->fetchTagData($resultText);
			if(!$resultText || count($resultText) < 1){
				
			}
			
			$listdata = array_slice($resultText[1],12);
			
			$cnt = 0;
			$isWrap = 0;
			foreach($listdata as $key=>$data){
				$data = trim(strip_tags($data));
				if(strlen($data) < 1){
					continue;
				}
				if($data == '暂时没有数据！'){
					file_put_contents(__ROOT__.'/new_stockid.txt',$stokcData['identity']."\r\n",FILE_APPEND);
				}
				$sdata = $data;
				$data = mb_convert_encoding($data,'gb2312','utf8');
				echo $data.'>>';
				if(strpos($sdata,'查看') !== false){
					
						
							
					$lastInsertId = 0;
					$revolution = trim(strip_tags($listdata[$key-8]));
					$revolution = strtotime($revolution);
					if($revolution){
						$cash = trim(strip_tags($listdata[$key-5]));
						$bonus = trim(strip_tags($listdata[$key-6]));
						$debt = trim(strip_tags($listdata[$key-7]));
						$register_date = trim(strip_tags($listdata[$key-2]));
						$without_date = trim(strip_tags($listdata[$key-3]));
						$market_date = trim(strip_tags($listdata[$key-1]));
						
						$where = array();
						$where['revolution'] = $revolution;
						$where['stock_identity'] = $stokcData['identity'];
						$dividendData = $this->model('SecuritiesDividend')->where($where)->count();
						if(!$dividendData){
								
							$setarr = array(
								'revolution'=>$revolution,
								'cash'=>$cash,
								'stock_identity'=>$stokcData['identity'],
								'bonus'=>$bonus,
								'debt'=>$debt,
								'register_date'=>strtotime($register_date),
								'without_date'=>strtotime($without_date),
								'market_date'=>strtotime($market_date),
							);
							$setarr['sn'] = $this->get_sn();
							$setarr['dateline'] = $this->getTime();
							$setarr['lastupdate'] = $setarr['dateline'];
							$setarr['subscriber_identity'] = $this->session('uid');
							
							$lastInsertId = $this->model('SecuritiesDividend')->data($setarr)->add();
							
							if($cash >0 && $stokcData['univalent'] > 0 && ($cash/10/$stokcData['univalent']) > 0.05){
								//高股息
								$this->service('MessengerMessage')->sendMobileMms(13883095702,$stockData['title'].'分红：'.$cash);
							}
							
							if(($bonus+$debt) > 5){
								//高拆股
								$this->service('MessengerMessage')->sendMobileMms(13883095702,$stockData['title'].'拆股：'.($bonus+$debt));
							}
							
						}else{
							$setarr = array(
								'register_date'=>strtotime($register_date),
								'without_date'=>strtotime($without_date),
								'market_date'=>strtotime($market_date),
							);
							
							$where = array(
								'identity'=>$dividendData['identity']
							);
							$lastInsertId = $this->model('SecuritiesDividend')->data($setarr)->where($where)->save();
						}
					}
					echo $lastInsertId;
					echo "\r\n";
					
				}
			}
			
		}
	}
}