<?php
/**
 * 经纪商
 */
class BrokerConsole extends Console {
	
    public function fire(){
		
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