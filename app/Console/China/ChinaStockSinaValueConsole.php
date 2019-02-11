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
 
class ChinaStockSinaValueConsole extends Console {
	
	public function fire(){
		
		include_once __ROOT__.'/vendor/PHPBamboo/extend/Snoopy/Snoopy.class.php';
		
		
		$listdata = array(
			array('code'=>'000001')
		);
		
		$start = 5;
		$perpage = 500;
		
		while(true){
			$where = array(
				'valuation'=>0
			);
			$listdata = $this->model('SecuritiesStock')->field('identity,title,symbol')->where($where)->orderby('identity desc')->limit($start,$perpage,4000)->select();
		
			foreach($listdata as $catId=>$symbolData){
				
				$snoopy = new Snoopy();
				
				$len = strlen($symbolData['symbol']);
				if($len < 6){
					$temp = array();
					$len = 6-$len;
					for($i=0;$i<$len;$i++)
					{
						$temp[] = 0;
					}
					$symbolData['symbol'] = implode('',$temp).$symbolData['symbol'];
				}
				
				$url = 'http://vip.stock.finance.sina.com.cn/corp/go.php/vFD_FinancialGuideLine/stockid/'.$symbolData['symbol'].'/displaytype/4.phtml';
				echo $url."\r\n";
				
				
				$snoopy->agent = $this->agentList[mt_rand(0,count($this->agentList)-1)];
				//$snoopy->referer = "http://www.dongqiudi.com";
				$snoopy->cookies["SessionID"] = md5(time());
				$snoopy->rawheaders["Pragma"] = "no-cache";
				$ip = $this->getIp();
				$snoopy->rawheaders["X_FORWARDED_FOR"] = $ip; //伪装ip
				$snoopy->rawheaders["CLIENT-IP"] = $ip; //伪装ip
				
				$snoopy->fetch($url); 
				$content = $snoopy->results;
				if(!$content){
					echo 'failed';
				}
				
				list(,$content) = explode('<table id="BalanceSheetNewTable0" width="100%">',$content);
				list($content) = explode('<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table2">',$content);
				
				$resultText = $this->helper('html')->fetchTagData($content);
				$resultTextList = array_slice($resultText[1],0,55);
				//净资产
				$a = trim(strip_tags($resultTextList[35]));
				$a = is_numeric($a)?$a:0;
				//公积金
				$b = trim(strip_tags($resultTextList[45]));
				$b = is_numeric($b)?$b:0;
				//未分配
				$c = trim(strip_tags($resultTextList[50]));
				$c = is_numeric($c)?$c:0;
				
				$where = array(
					'identity'=>$symbolData['identity']
				);
				var_dump($a,$b,$c);
				
				$stockData = array(
					'valuation'=>$a+$b+$c
				);
				
				$lastRows = $this->model('SecuritiesStock')->data($stockData)->where($where)->save();
				
				echo $lastRows."\r\n";
				sleep(3);
				
			}
			$start++;
		}
	}
	
	private function getIp(){
		$ip=mt_rand(11, 191).".".mt_rand(0, 240).".".mt_rand(1, 240).".".mt_rand(1, 240);   //随机ip  
		return $ip;
	}
	
	public function getAllImage($text){
		if(!$text){
			return '';
		}
		
		
		$remoteAttach = array();
		$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/"; 
		preg_match_all($pattern,$text,$match); 
		$imgList = $match[1];
		if($imgList){
			foreach($imgList as $key=>$img){
				$remoteAttach[] = array($img,$this->downFile($img,'',60,1));
			}
			
			
		}
		return $remoteAttach;
		
	}
	
	public function downFile($url, $file="", $timeout=60,$return = 0){
		$attachmentId = 0;
		$attachFullAddress = '';
		if(!$file){
			$fileData = pathinfo($url);
			$attach = date('Ym/dH').'/'.md5($url).'.'.$fileData['extension'];
			$file = __ROOT__.'/data/attachment/'.$attach;
			$attachFullAddress = str_replace(__ROOT__,'',$file);
		}
		$folder = dirname($file);
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名 
			
			$temp = curl_exec($ch);
			
			file_put_contents($file, $temp);
			$imageInfo = getimagesize($file);
			if($imageInfo){
				if($return){
					return $attachFullAddress;
				}
				$filesize = filesize($file);
				$dateline = $this->getTime();
				$setarr = array(
					'supplier_identity'=>0,
					'filename'=>current(explode('.',$fileName)),
					'filesize'=>$filesize,
					'filetype'=>$fileData['extension'],
					'attach'=>$attach,
					'remote'=>0,
					'lastupdate'=>$dateline,
				);
			
				$setarr['sn'] = $this->get_sn();
				$setarr['dateline'] = $dateline;
				$setarr['subscriber_identity'] = 1;
				$attachmentId = $this->model('MediaAttachment')->data($setarr)->add();
			}
		}
		return $attachmentId;
	}
	
}