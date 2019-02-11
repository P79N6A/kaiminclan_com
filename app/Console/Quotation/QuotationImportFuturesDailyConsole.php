<?php
class QuotationImportFuturesDailyConsole extends Console {
	
	public function fire(){
		
				
		$folder = __DATA__.'/futures/';
		$tempFolder = __STORAGE__.'/futures';
		$targetFolder = $folder.'/old';
		$newFolder = $folder.'/new';
		if(!is_dir($targetFolder)){
			mkdir($targetFolder,0777,1);
		}
		if(!is_dir($tempFolder)){
			mkdir($tempFolder,0777,1);
		}
		if(!is_dir($newFolder)){
			mkdir($newFolder,0777,1);
		}
		$cnt = 0;
		$max = 1;
		list(,,$start) = $_SERVER['argv'];
		$start = intval($start) > 0?$start:1;
		
		$handle = opendir($folder);
		while($filename = readdir($handle)){
			if(in_array($filename,array('.','..'))){
				continue;
			}
			$this->info($filename);
			$symbol = substr($filename,0,6);
			$where = array(
				'code'=>$symbol
			);
			$tempFile = $tempFolder.'/'.$filename;
			$newFile = $targetFolder.'/'.$filename;
			$sourceFile = $folder.'/'.$filename;		
			rename($sourceFile,$tempFile);
			
			$symbolData = $this->model('ForeignContact')->where($where)->find();
			if(!$symbolData){
				rename($filename,$newFolder.'/'.$filename);
				continue;
			}
			$fileData = file($tempFile);	
			
			
		
			$fileData = array_slice($fileData,2,-1);
			if(count($fileData) < 1){
				continue;
			}
			foreach($fileData as $key=>$data){
				list($curTime,$open,$high,$low,$close,$valume,$amount) = explode(',',$data);
				$curTime = str_replace('.','-',$curTime);
				$this->info($symbol.'>>'.$curTime);
				$curTime = strtotime($curTime);
				
				$setarr = array(
					'id'=>$symbolData['identity'],
					'cycle'=>($curTime),
					'open'=>$open,
					'low'=>$low,
					'high'=>$high,
					'close'=>$close,
					'amount'=>$close,
					'valume'=>$close,
					'ema'=>0,
					'wma'=>0,
					'slow'=>0,
					'fast'=>0,
					'signal'=>0,
				);
				
				$table = 'futures_'.date('Y',$curTime);
				
				$this->model('QuotationMarket')->subtable($table)->data($setarr)->add();
			}
			die('test');
		
		}
	}
	
}