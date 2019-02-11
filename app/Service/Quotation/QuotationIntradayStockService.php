<?php
/**
 *
 * 数据收集
 *
 * 统计分析
 *
 */
class QuotationIntradayStockService extends QuotationToolService 
{
	protected $appid = 6;
	
	
	protected $dataType = 1;
	
	
	public function symbol($symbol){
		$this->dataId= $symbol;
		return $this;
	}
	
	public function close($close){
		$this->data('close',$close);	
		$this->quotataion[4] = $close;
		return $this;	
	}
	
	public function open($open){
		$this->data('open',$open);
		$this->quotataion[1] = $open;
		return $this;
	}
	
	public function low($low){
		$this->data('low',$low);
		$this->quotataion[3] = $low;
		return $this;
	}
	
	public function high($high){
		$this->data('high',$high);
		$this->quotataion[2] = $high;
		return $this;
	}
	
	public function period($period){
		$this->data('period',$period);
		$this->quotataion[0] = $period;
		return $this;
	}
	
}