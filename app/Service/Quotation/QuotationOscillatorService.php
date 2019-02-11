<?php
/**
 *
 * 随机震荡
 *
 * 统计分析
 *
 */
class QuotationOscillatorService extends Service 
{
	private static $obj;
	
	private $setting = array();
	private $_data = array();
	private $cycle = 0;
	private $dataId = 0;
	public function init(){		
		require_once __ROOT__.'/vendor/PHPSignal/PHPOscillator.php';
		if(empty(self::$obj)){
			self::$obj = new PHPOscillator(__STORAGE__);
		}
	}
	public function data($dataId,$cycle,$setting,$data){
		$this->setting = $setting;
		//array('cycle'=>$cycle,'open'=>$open,'high'=>$high,'low'=>$low,'close'=>$close)
		$this->_data = $data;
		$this->cycle = $cycle;
		$this->dataId = $dataId;
		return $this;
	}
	
	public function get(){
		if(empty(self::$obj)){
			self::$obj = new PHPOscillator(__STORAGE__);
		}
		
		$this->_data['cycle'] = $this->cycle;
		
		$oscillatorData = self::$obj->setSymbol($this->dataId)->kdj($this->setting,$this->_data);
		
		if($oscillatorData['signal'] < 25){
			//超买
		}
		if($oscillatorData['signal'] > 80){
			//超卖
			
		}
		
		return $oscillatorData;
	}
}