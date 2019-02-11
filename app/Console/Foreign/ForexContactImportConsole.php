<?php

class ForexContactImportConsole extends Console {
	
	public function fire(){
		$filename = __DATA__.'/forex-currency.txt';	
		$fileData = file($filename);
		
		foreach($fileData as $key=>$symbol){
			$symbol = strtolower(trim($symbol));
			$this->info($symbol);
			
			$symbol = strtoupper($symbol);
			$begin = substr($symbol,0,3);
			$end = substr($symbol,3,3);
			$currency = substr($symbol,0,3);
			$currencyList = $this->service('ForeignCurrency')->getCurrencyList(array('code'=>array($begin,$end)),0,2);
			if($currencyList['total'] < 1){
				$this->info($begin.'|'.$end.'不存在');
			}else{
				if($currencyList['total'] < 2){
					$currencyData = current($currencyList['list']);
					$addCurr = '';
					if($currencyData['code'] != $begin){
						$addCurr = $begin;
					}
					if($currencyData['code'] != $end){
						$addCurr = $end;
					}
					$this->service('ForeignCurrency')->insert(array('title'=>$addCurr,'code'=>$addCurr));
				}
			}
		}
	}
	public function import(){
		$filename = __DATA__.'/forex-currency.txt';	
		$fileData = file($filename);
		
		foreach($fileData as $key=>$symbol){
			$symbol = strtolower(trim($symbol));
			$this->info($symbol);
			
			$begin = substr($symbol,0,3);
			$end = substr($symbol,3,3);
			$currency = substr($symbol,0,3);
			$currencyData = $this->service('ForeignCurrency')->getCurrencyList(array('code'=>array($begin,$end)),0,2);
			if($currencyData['total'] < 1){
				$this->info($currency.'不存在');
				continue;
			}
			$currencyData = current($currencyData['list']);
			$currencyId = $currencyData['identity'];
			
			$this->info($currencyData['title']);
			continue;
			if(!$this->service('ForeignContact')->checkContactTitle($symbol)){
				
			
				$setarr = array(
					'title' => $symbol,
					'code' => $symbol,
					'currency_identity' => $currencyId,
					'remark' => $symbol
				);
				
				$this->service('ForeignContact')->insert($setarr);
			}
		}
	}
}