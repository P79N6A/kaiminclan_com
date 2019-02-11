<?php
class PHPOscillator  {
	private $symbol = '';
	
	public function __construct ($folder){
		if(!$folder){
			$folder = dirname(__FILE__);
		}
		
		$folder = $folder.'/signal/kdj';
		
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		$this->folder = $folder;
		
	}
	
	public function setSymbol($symbol){
		$this->symbol = $symbol;
		
		return $this;
	}
	
	/**
	 * 最大值
	 *
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function hhv($price,$day,$cycle){
		$hhv = array();
		
		
		$filename = $this->folder.'/hhv/'.$this->symbol.'_'.$day.'.json';
		$folder = dirname($filename);
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		if(is_file($filename)){
			$historyData = file_get_contents($filename);
			if($historyData){
				$historyData = json_decode($historyData,true);
				if($historyData){
					$dataLen = count($historyData);
					if($dataLen >= $day){
						$historyData = array_slice($historyData,1);
					}
					$hhv =  $historyData;
				}
			}
		}
		
		$oldHhv = 0;
		if(isset($hhv[$cycle])){
			$oldHhv = $hhv[$cycle];
		}
		if($oldHhv != $price){		
			$hhv[$cycle] = $price;
		}
		file_put_contents($filename,json_encode($hhv));
		
		return max(array_values($hhv));
	}
	/**
	 * 最小值
	 *
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function llv($price,$day,$cycle){
		
		$llv = array();
		
		$filename = $this->folder.'/llv/'.$this->symbol.'_'.$day.'.json';
		$folder = dirname($filename);
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		if(is_file($filename)){
			$historyData = file_get_contents($filename);
			if($historyData){
				$historyData = json_decode($historyData,true);
				if($historyData){
					$dataLen = count($historyData);
					if($dataLen >= $day){
						$historyData = array_slice($historyData,1);
					}
					$llv=  $historyData;
				}
			}
			unset($historyData);
		}
		
		$oldLlv = 0;
		if(isset($llv[$cycle])){
			$oldLlv = $llv[$cycle];
		}
		if($oldLlv != $price){		
			$llv[$cycle] = $price;
		}	
		
		file_put_contents($filename,json_encode($llv));
		
		
		return min(array_values($llv));
	}
	/**
	 * 指数平滑移动平均
	 *
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	public function ema($ema,$price,$day){
		return round((2*$price+($day-1)*$ema)/($day+1),8);
	}
	/**
	 * KDJ震荡
	 *
	 * @param $setting 
	 * @param $quotation
	 * @param $kdj
	 *
	 */
	public function kdj($setting,$quotation){
		
		extract($setting);
		if(!isset($fast) || !isset($slow) || !isset($signal)){
			die('no define fast slow signal');
		}
		
		extract($quotation);
		if(!isset($cycle) || !isset($open) || !isset($high) || !isset($low) || !isset($close)){
			die('no define open high low'.$cycle.'>>o'.$open.'>>h'.$high.'>>l'.$low.'>>c'.$close);
		}
		
		
		$kdj = array(
			'fast'=>0,'slow'=>0,'signal'=>0
		);
		$filename = $this->folder.'/signal/'.$this->symbol.'_'.$fast.'_'.$slow.'_'.$signal.'.json';
		$folder = dirname($filename);
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		if(is_file($filename)){
			$historyData = file_get_contents($filename);
			if($historyData){
				$historyData = json_decode($historyData,true);
				if($historyData){
					$kdj =  $historyData;
				}
			}
		}	
 
		$output = $kdj;
		if(!isset($kdj[$cycle])){	
		
			$low = $this->llv($low,$fast,$cycle);
			$rsv = ($close-$low)/($this->hhv($high,$fast,$cycle)-$low)*100;	
			
			
			$k = $kdj['fast'];
			$k = empty($k)?1:$k;
			//$k = 2/3*$k+1/3*$rsv;
			$k = $this->ema($k,$rsv,$slow);
			$k = round($k,8);
			if($k < 0){
				$k = 0;
			}
			if($k > 100){
				$k = 100;
			}
			
			$d = $kdj['slow'];
			$d = empty($d)?1:$d;
			//$d = 2/3*$d+1/3*$k;
			$d = $this->ema($d,$k,$signal);
			
			$d = round($d,8);
			if($d < 0){
				$d = 0;
			}
			if($d > 100){
				$d = 100;
			}
			
			$j = 3*$k-2*$d;
			$j = round($j,8);
			if($j < 0){
				$j = 0;
			}
			if($j > 100){
				$j = 100;
			}
			
			$output = array($cycle=>array('fast'=>$k,'slow'=>$d,'signal'=>$j));
			
			file_put_contents($filename,json_encode($output));
		}
		return $output[$cycle];
	}
}