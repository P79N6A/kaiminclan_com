<?php
class PHPDirection  {
	private $day = 0;
	
	private $data = array();
	
	private $folder;
	
	private $output = array();
	public function __construct ($folder){
		if(!$folder){
			$folder = dirname(__FILE__);
		}
		
		$folder = $folder.'/signal';
		
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		$this->folder = $folder;
		
	}
	
	public function setData($symbol,$data,$day){
		extract($data);
		foreach($day as $index=>$_day){
		
			$filename = $this->folder.'/'.$index.'/'.$symbol.'_'.$_day.'.json';
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
						if($dataLen >= $_day){
							$historyData = array_slice($historyData,1);
						}
						$this->data =  $historyData;
					}
				}
				unset($historyData);
			}
			
			switch($index){
				case 'ema':
					$this->output[$index] = $this->ema(current($this->data),$close,$_day);
					$this->data[$cycle] = $this->output[$index];
				break;
				case 'wma':
					$this->data[$cycle] = $close;
					
					$this->day = $_day;
					$this->output[$index] = $this->wma();
				break;
			}
			
			file_put_contents($filename,json_encode($this->data));
		}

		
		return $this;
	}
	
	public function getLeft(){
		$leftVal = 0;		
		$dataLen = count($this->data);	
		for($i=$this->day;$i>0;$i--){
			$cnt= $i;
			$cnt--;
			//echo "leftVal:".$i."\r\n";
			if(!isset($this->data[$cnt])) continue;
			$leftVal += $i*$this->data[$cnt];
		}	
		//var_dump($leftVal); die();
		
		return $leftVal;
	}
	
	public function get(){
		return $this->output;
	}
	
	private function getRight(){
		$rightVal = 0;
		for($i=$this->day;$i> 0;$i--){
			$rightVal += $i;
		}
		return $rightVal;
	}
	/**
	 * 指数平滑移动平均
	 *
	 * @param $price 
	 * @param $day 
	 *
	 */
	public function wma(){
		$leftVal = $this->getLeft();
		$rightVal = $this->getRight();
		return $this->getLeft()/$this->getRight();
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
		return (2*$price+($day-1)*$ema)/($day+1);
	}
}