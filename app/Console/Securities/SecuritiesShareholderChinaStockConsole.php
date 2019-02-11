<?php
/**
 * 主要股东
 *
 *证券
 */
class ShareholderChinaStockConsole extends Console {
	
	protected $initial = 0;
	
	protected $api = 'http://vip.stock.finance.sina.com.cn/corp/go.php/vCI_StockHolder/stockid/{_SYMBOL_}.phtml';
	
	
	public function fire(){
				
		$start = $_SERVER['argv'][2];
		$start = intval($start) <1 ?1:$start;
		
		$cnt = 0;
		$max = 30;
		
		$startYear = 0;
		if($this->initial < 1){
			$month = date('n');
			if($month < 3){
				$startYear = date('Y').'-12-31';
			}
			elseif($month < 6){
				$startYear = date('Y').'-03-31';
			}
			elseif($month < 10){
				$startYear = date('Y').'-06-30';
			}
			elseif($month < 12){
				$startYear = date('Y').'-09-30';
			}
			$startYear = strtotime($startYear);
		}
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
		
		$listdata = $this->model('SecuritiesStock')->field('identity,title,symbol,ipo_date')->where($where)->limit($start,$perpage,$count)->select();
		if(!$listdata){
			var_dump('no data');
			die();
		}
		
		foreach($listdata as $key=>$stokcData){
						
			$symbol = $stokcData['symbol'];
			$this->info($stokcData['title']);
			$where = array();
			$where['deadline_date'] = $startYear;
			$where['stock_identity'] = $stokcData['identity'];
			$shareHolderCount = $this->model('SecuritiesShareHolder')->where($where)->count();		
			if($shareHolderCount){
				continue;
			}
			
					
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
				$content = $this->cache($cacheKey);
				if(!$content){
					$content = $this->loadUrlData($url);
					if(!$content){
						echo 'failed';
					}
					$this->cache($cacheKey,$content);
				}
			}
			

			
			$content = mb_convert_encoding($content,'utf8','gb2312');
		
		
			list(,$resultText) = explode('<table width="100%" id="Table1">',$content);
			list($resultText) = explode('<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table2">',$resultText);
			$resultText = $this->helper('Html')->fetchTagData($resultText);
			if(!$resultText || count($resultText) < 1){
				
			}
			
			$listdata = array_slice($resultText[1],5);
			//var_dump(mb_convert_encoding($listdata[0],'gbk','utf8')); die();
			
			$cnt = 0;
			$isWrap = 0;
			foreach($listdata as $key=>$data){
				
				
				$data = trim(strip_tags($data));
				if(strlen($data) < 1){
					continue;
				}
				$sdata = $data;
				$data = mb_convert_encoding($data,'gb2312','utf8');
				if(strpos($sdata,'编号') !== false){
					//截至
					$last = trim(strip_tags($listdata[$key-15]));
					//公告
					$common = trim(strip_tags($listdata[$key-12]));
					//总数
					$total = str_replace('查看变化趋势','',trim(strip_tags($listdata[$key-6])));
					//平均股
					$ave = trim(str_replace('查看变化趋势','',trim(strip_tags($listdata[$key-2]))));
					$ave = str_replace('股(按总股本计算)','',$ave);
					
				}
				if($last < 1){
					continue;
				}
				if($startYear > 0 && strtotime($last) < $startYear){
					continue;
				}
				
				$lastInsertId = 0;
				
				if(strpos($sdata,'A股') !== false || strpos($sdata,'限售流通股') !== false){
					
					$weight = trim(strip_tags($listdata[$key-2]));
					$quantity = trim(strip_tags($listdata[$key-4]));
					$company = trim(strip_tags($listdata[$key-6]));	
					if(is_numeric($company)){
						file_put_contents('./expersion_shareholder.txt',serialize($listdata),FILE_APPEND);
					}
					$where = array();
					$where['title'] = $company;
					$companyData = $this->model('SecuritiesMechanism')->data('identity')->where($where)->find();
					if(!$companyData){
					
							
						$setarr = array(
							'title'=>$company,
						);
						$setarr['sn'] = $this->get_sn();
						$setarr['dateline'] = $this->getTime();
						$setarr['lastupdate'] = $setarr['dateline'];
						$setarr['subscriber_identity'] = $this->session('uid');
						
						$comapnyId = $this->model('SecuritiesMechanism')->data($setarr)->add();
					}else{
						$comapnyId = $companyData['identity'];
					}
					
					
				
					$where = array();
					$where['deadline_date'] = strtotime($last);
					$where['stock_identity'] = $stokcData['identity'];
					$where['mechanism_identity'] = $comapnyId;
					$shareHolderCount = $this->model('SecuritiesShareHolder')->where($where)->count();					
					if($shareHolderCount < 1){
							
						$setarr = array(
							'quantity'=>str_replace('&nbsp;','',$quantity),
							'weight'=>str_replace('&nbsp;','',$weight),
							'stock_identity'=>$stokcData['identity'],
							'deadline_date'=>strtotime($last),
							'notice_date'=>strtotime($common),
							'mechanism_identity'=>$comapnyId,
						);
						$setarr['sn'] = $this->get_sn();
						$setarr['dateline'] = $this->getTime();
						$setarr['lastupdate'] = $setarr['dateline'];
						$setarr['subscriber_identity'] = $this->session('uid');
						
						$lastInsertId = $this->model('SecuritiesShareHolder')->data($setarr)->add();
					}
				}
				$this->info($symbol.'>>'.$last.'>>'.$lastInsertId);
					
			}
			file_put_contents(__ROOT__.'/new_stockid.txt',$stokcData['identity']."\r\n",FILE_APPEND);
			
		}
		$this->unlock();
	}
}