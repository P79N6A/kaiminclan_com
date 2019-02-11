<?php
/**
 *
 * 趋势
 *
 * 统计分析
 *
 */
class QuotationDirectionService extends Service 
{
	private static $obj;
	
	private $setting = array();
	private $_data = array();
	private $cycle = 0;
	private $dataId = 0;
	public function init(){		
		require_once __ROOT__.'/vendor/PHPSignal/PHPDirection.php';
		if(empty(self::$obj)){
			self::$obj = $this->newInstantce();
		}
	}
	
	private function newInstantce(){
		return new PHPDirection(__STORAGE__);
	}
	public function data($dataId,$cycle,$setting,$data){
		$this->setting = $setting;
		//array('cycle'=>$cycle,'close'=>$close)
		$this->_data = $data;
		$this->cycle = $cycle;
		$this->dataId = $dataId;
		return $this;
	}
	
	public function get(){
		if(empty(self::$obj)){
			self::$obj = $this->newInstantce();
		}
		
		$this->_data['cycle'] = $this->cycle;
		
		$powerData = self::$obj->setData($this->dataId,$this->_data,$this->setting)->get();
		
		if($oscillatorData['signal'] < 25){
			//超买
		}
		if($oscillatorData['signal'] > 80){
			//超卖
			
		}
		
		return $powerData;
	}
}