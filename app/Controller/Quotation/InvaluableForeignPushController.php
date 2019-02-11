<?php
/**
 *
 * 信号编辑
 *
 * 20180301
 *
 */
class InvaluableForeignPushController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'symbol'=>array('type'=>'letter','tooltip'=>'品种'),
			'open'=>array('type'=>'money','tooltip'=>'开盘价'),
			'high'=>array('type'=>'money','tooltip'=>'最高价'),
			'low'=>array('type'=>'money','tooltip'=>'最低价'),
			'close'=>array('type'=>'money','tooltip'=>'收盘价'),
			'today'=>array('type'=>'date','tooltip'=>'日期')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$symbol = $this->argument('symbol');
		$open = $this->argument('open');
		$high = $this->argument('high');
		$low = $this->argument('low');
		$close = $this->argument('close');
		$today = $this->argument('today');
		
		
		$where  = array(
			'code'=>strtolower($symbol)
		);
		
		$foreignList = $this->service('ForeignContact')->getContactList($where,0,1);
		if($foreignList['total'] < 1){
			$this->info($symbol.'不存在',400001);
		}
		
		$foreignId = $foreignList['list']['identity'];
		
		
				
		$oscillatorData = $this->service('QuotationOscillator')->data(
			$foreignId,
			5,
			array('fast'=>450,'slow'=>200,'signal'=>125),
			array('open'=>$open,'close'=>$close,'low'=>$low,'high'=>$high)
		)->get();
		
		
		$powerData = $this->service('QuotationDirection')->data(
			$foreignId,
			5,
			array('ema'=>480,'wma'=>960),
			array('close'=>$close)
		)->get();
		
		$setarr = array(
			'stock_identity'=>$foreignId,
			'cycle'=>strtotime($curTime),
			'open'=>$open,
			'low'=>$low,
			'high'=>$high,
			'close'=>$close,
			'signal'=>$oscillatorData['signal'],
			'slow'=>$oscillatorData['slow'],
			'fast'=>$oscillatorData['fast'],
			'ema'=>$powerData['ema'],
			'wma'=>$powerData['wma'],
		);
		
		$table = 'foreign_intraday_'.date('Y_m');
		
		$this->model('QuotationIntradaySecurites')->subtable($table)->data($setarr)->add();
		
		
	}
}
?>