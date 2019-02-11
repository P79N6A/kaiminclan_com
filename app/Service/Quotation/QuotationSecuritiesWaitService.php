<?php
/**
 *
 * 数据收集
 *
 * 统计分析
 *
 */
class QuotationSecuritiesWaitService extends QuotationToolService 
{
	protected $appid = 5;
		
	protected $dataType = 1;
	
	protected $dataId = 0;
	
	
	public function newCompany($total){
		$this->data('company',$total);
		return $this;
	}
	
}