<?php
 
class SingnalTestConsole extends Console {
	
	
	public function fire(){		
		
		
		$signal =  json_encode(array(
		'oscillator'=>array(array('code'=>'ema','weight'=>120,'id'=>9),array('code'=>'wma','weight'=>240,'id'=>10)),
		'direction'=>array(array('code'=>'fast','weight'=>10,'id'=>6),array('code'=>'slow','weight'=>8,'id'=>7),array('code'=>'signal','weight'=>5,'id'=>8))
		));
			file_put_contents(__DATA__.'/signal.TXT',$signal);
		die();
		
		require_once __ROOT__.'/vendor/PHPSignal/PHPEma.php';
		require_once __ROOT__.'/vendor/PHPSignal/PHPWma.php';
		require_once __ROOT__.'/vendor/PHPSignal/PHPKdj.php';
		
		$file = __DATA__.'/SH#600009.txt';
		if(!is_file($file)){
			$this->error('文件不存在');
		}
		
		$filedata = file($file);
		
		$filedata = array_slice($filedata,1,-1);
		if(count($filedata) < 1){
			$this->error('数据不存在');
		}
		
		$kdjObj = new PHPKdj(__STORAGE__);
		$emaObj = new PHPEma();
		$wmaobj = new PHPWma(__STORAGE__);
		$ema = 0;
		$kdj = array(0,0,0);
		foreach($filedata as $key=>$data){
			list($curTime,$open,$high,$low,$close,$valume,$amount) = explode(',',$data);
			if($ema < 1){
				$ema = $low;
			}
			
			$symbol = 600009;
			$ema = $emaObj->ema($ema,$close,120);
			$wma = $wmaobj->setData($symbol,$close,240)->get();
			$kdj = $kdjObj->setSymbol($symbol)->kdj(array(10,8,5),array($open,$high,$low,$close),$kdj);
			file_put_contents(__DATA__.'/6000009.TXT','时间：'.$curTime.'.趋势:ema:'.$ema.'>wma240:'.$wma.':KDJ:'.(implode(',',$kdj)).'>>'.($ema>$wma?'多':'空')."\r\n",FILE_APPEND);
			//$this->info('kdj:'.implode(',',$kdj));
			$this->info('趋势:ema:'.$ema.'>wma240:'.$wma.'kdj:'.implode(',',$kdj));
		}
		
	}
	
}
