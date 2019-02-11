<?php
/**
 *
 * 证券
 *
 * 新股申购
 *
 *
 */
 
class ChinaStockNewConsole extends Console {
	
	
	
	public function fire(){
			
	
		
		$url = 'http://vip.stock.finance.sina.com.cn/corp/go.php/vRPD_NewStockIssue/page/1.phtml';
		echo $url;
		
		
		$cacheKey = md5($url.date('Ymd'));
		$content = $this->cache($cacheKey);
		if(!$content){
			$this->info( '>>获取远程数据.'); 
			$content = $this->loadUrlData($url);
			if(!$content){
				echo 'failed';
			}
			$this->cache($cacheKey,$content);
		}
		
		$content = mb_convert_encoding($content,'utf8','gb2312');
		
		list(,$content) = explode('<table id="NewStockTable">',$content);
		list($content) = explode('<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table2">',$content);
		
		$content = str_replace('<font color=\'red\'>*</font>','',$content);
		$resultText = $this->helper('html')->fetchTagData($content);
		if($resultText){
			$listdata = $resultText[1];
			if(!$listdata){
				
			}
		}
		
		$listdata = array_slice($resultText[1],35);
		
		
		foreach($listdata  as $key=>$data){
			$data =	$this->getHtmlVal($data);
			
			if($data == '查看'){
				//发行日期
				$symbol = $this->getHtmlVal($listdata[$key-13]);
				$market_date = $this->getHtmlVal($listdata[$key-8]);
				$new = $this->getHtmlVal($listdata[$key-9]);
				$title = $this->getHtmlVal($listdata[$key-11]);
				$this->info($symbol); 
				if(strpos($symbol,'-') !== false){
					continue;
				}
				
				
				$detailData = $this->getDetail($symbol);
				
				
				$lastInsertId = 0;
				$where = array(
					'symbol'=>$symbol
				);
				$market_date = strtotime($market_date);
				
				$symbolData = $this->model('SecuritiesStock')->where($where)->find();
				if(!$symbolData){
					$exchangeId = 1;
					if(substr($symbol,0,2) == 60){
						$exchangeId = 2;
					}
					$setarr = array(
						'symbol'=>$symbol,
						'title'=>$title,
						'exchange_identity'=>$exchangeId,
						'fullname'=>$detailData['fullname'],
						'english'=>$detailData['en'],
						'registered_address'=>$detailData['regAddr'],
						'office_address'=>$detailData['offAddr'],
						'business'=>$detailData['note'],
						'dateline'=>$this->getTime()
					);
					if($market_date){
						$setarr['ipo_date'] = $market_date;
						$setarr['status'] = 0;
					}else{
						$setarr['status'] = 5;
					}
					$lastInsertId = $this->model('SecuritiesStock')->data($setarr)->add();
					$this->service('QuotationSecuritiesWait')->newCompany(1)->add();
					if($market_date){
						$setarr = array(
							'idtype'=>3,
							'id'=>$lastInsertId,
							'code'=>$symbol,
							'title'=>$title,
							'dateline'=>$this->getTime()
						);
						$this->model('QuotationSymbol')->data($setarr)->add();
					}
				}elseif($symbolData['status'] == 5){
					if($market_date){
						$where = array(
							'identity'=>$symbolData['identity']
						);
						
						$setarr = array(
							'idtype'=>3,
							'id'=>$symbolData['identity'],
							'code'=>$symbol,
							'title'=>$title,
							'dateline'=>$this->getTime()
						);
						$this->model('QuotationSymbol')->data($setarr)->add();
						
						//$this->model('QuotationSymbol')->data(array('lastupdate'=>$setarr['dateline']))->where($where)->save();
					}
				}
				echo $lastInsertId."\r\n";
			}else{
				//$this->info($data,'>>');
			}
		}
		
	}
	
	public function getDetail($symbol){
		$url = 'http://vip.stock.finance.sina.com.cn/corp/go.php/vCI_CorpInfo/stockid/'.$symbol.'.phtml';
		
		$cacheKey = md5($url.date('Ymd'));
		$content = $this->cache($cacheKey);
		if(!$content){
			$this->info('>>获取'.$symbol.'详细数据.'); 
			$content = $this->loadUrlData($url);
			if(!$content){
				echo 'failed';
			}
			$this->cache($cacheKey,$content);
		}		
		
		$content = mb_convert_encoding($content,'utf8','gb2312');
		
		list(,$content) = explode('<table id="comInfo1" width="100%">',$content);
		list($content) = explode('<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table2">',$content);
		
		$content = mb_convert_encoding($content,'gb2312','utf8');
		
		$listdata = array();
		$resultText = $this->helper('html')->fetchTagData($content);
		if($resultText){
			$listdata = $resultText[1];
			if(!$listdata){
				
			}
		}
		
		$detail = array(
			'fullname'=>$this->getHtmlVal($listdata[1]),
			'en'=>$this->getHtmlVal($listdata[3]),
			'url'=>$this->getHtmlVal($listdata[37]),
			'regAddr'=>$this->getHtmlVal($listdata[47]),
			'offAddr'=>$this->getHtmlVal($listdata[49]),
			'note'=>$this->getHtmlVal($listdata[53]),
		);
		
		return $detail;
	}
	
	
}