<?php
/**
 *
 * 数据收集
 *
 * 统计分析
 *
 */
class QuotationToolService extends Service 
{
	protected $appid;
	protected $dataId;
	
	private $folder = '';
	
	private $period = array();
	
	protected $framework = array();
	
	protected $signal = array();
	
	protected $quotataion = array();
	
	protected $data = array();
	
	public function init(){
		$where = array(
			'identity'=>$this->appid
		);
		$principalData = $this->model('QuotationPrincipal')->where($where)->find();
		if(!$principalData){
			return -1;
		}
		
		if($principalData['period']){
			$this->period  = explode(',',$principalData['period']);
		}
		
		if($principalData['code']){
			$this->folder  = $principalData['code'];
		}
		
		if($principalData['signal']){
			$this->signal  = json_encode($principalData['signal'],true);
		}
		
		
		$where = array(
			'principal_identity'=>$principalData['identity']
		);
		$frameworkList = $this->model('QuotationFramework')->field('identity,code,title')->where($where)->select();
		if(!$frameworkList){
			return -2;
		}
		
		foreach($frameworkList as $key=>$framework){
			$this->framework[$framework['code']] = $framework['identity'];
		}
		
	}
	
	protected function data($code,$value){
		
		if(isset($this->framework[$code])){
			
			$curTime = $this->getTime();
			$this->data['framework_identity'][] = $this->framework[$code];
			$this->data['id'][] = $this->dataId;
			$this->data['data'][] = $value;
			$this->data['cycle'][] = (date('Ymd',$curTime)).(1440);
			$this->data['dateline'][] = $curTime;
			$this->data['lastupdate'][] = $curTime;
		}
	}
	
	/***
	 *
	 * 分库
	 *
	 * 分表
	 *
	 */
	
	
	public final function add()	
	{
		if(empty($this->data)){
			return -1;
		}
		
		foreach($this->period as $key=>$period){
			switch($period){
				case 1:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Ymdhi'))->data($this->data)->replace();
				break;
				case 5:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Ymdh'))->data($this->data)->replace();
				break;
				case 15:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Ymdh'))->data($this->data)->replace();
				break;
				case 30:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Ymdh'))->data($this->data)->replace();
				break;
				case 60:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Ymd'))->data($this->data)->replace();
				break;
				case 240:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Ym'))->data($this->data)->replace();
				break;
				case 1440:
					$this->model('QuotationInvaluable')->subtable($this->folder.'_'.date('Y'))->data($this->data)->replace();
				break;
				case 28800:
				case 345600:
				
					$_periodVal = 0;
					if($period == 28800){
						$_periodVal = date('Ym').$period;
					}else{
						$_periodVal = date('Y').$period;
					}
				
					$where = array(
						'cycle'=>$_periodVal,
						'id'=>$this->dataId
					);
					
					$invaluableData = array();
					$invaluableList = $this->model('QuotationInvaluable')->subtable($this->folder)->where($where)->select();
					if($invaluableList){
						foreach($invaluableList as $cnt=>$invaluable){
							$invaluableData[$invaluable['framework_identity']] = $invaluable['data'];
						}
					}
					if(!$invaluableData){
						//新建
						foreach($this->data['cycle'] as $cnt=>$cycle){
							$this->data['cycle'][$cnt] = $_periodVal; 
						}
						$this->model('QuotationInvaluable')->subtable($this->folder)->data()->replace();
					}else{
						$invaluableNewData = array();
						$where = array();
						foreach($this->data['framework_identity'] as $cnt=>$frameworkId){
							$newData = $this->data['data'][$cnt];
							if($newData != $invaluableData[$frameworkId]){
								
								$where['framework_identity'] = $frameworkId;
								$where['id'] = $this->dataId;								
								$invaluableNewData['data'] = $newData;
								$invaluableNewData['lastupdate'] = $this->getTime;
								
								$this->model('QuotationInvaluable')->subtable($this->folder)->where($where)->save();
							}
						}
					}
				break;
			}
		}	
		
	}
	
	protected function signal($symbol,$period){
		
		require_once __ROOT__.'/vendor/PHPSignal/PHPEma.php';
		require_once __ROOT__.'/vendor/PHPSignal/PHPWma.php';
		require_once __ROOT__.'/vendor/PHPSignal/PHPKdj.php';
		
		if(empty($this->signal)){
			return -1;
		}
		
		if(empty($this->quotataion)){
			return -2;
		}
		
		list($curTime,$open,$high,$low,$close) = $this->quotataion;
		
		$subTableName = '';
		switch($period){
			case 1: $subTableName = $this->folder.'_'.date('YmdH',$curTime);  break;
			case 5: $subTableName = $this->folder.'_'.date('YmdH',$curTime);  break;
			case 15: $subTableName = $this->folder.'_'.date('Ymd',$curTime);  break;
			case 30: $subTableName = $this->folder.'_'.date('Ymd',$curTime);  break;
			case 60: $subTableName = $this->folder.'_'.date('Ymd',$curTime);  break;
			case 240: $subTableName = $this->folder.'_'.date('Ym',$curTime);  break;
			case 1440: $subTableName = $this->folder.'_'.date('Y',$curTime);  break;
			case 7200: $subTableName = $this->folder;  break;
			case 28800: $subTableName = $this->folder;  break;
			case 345600: $subTableName = $this->folder;  break;
		}
		
		
		
		$kdjObj = new PHPKdj(__STORAGE__);
		$emaObj = new PHPEma();
		$wmaobj = new PHPWma(__STORAGE__);
		
		$curTime = strtotime($curTime);
		$singalData = array();
		foreach($this->signal as $index=>$setting){
			switch($index){
				case 'ema':
					$ema = $emaObj->ema($ema,$close,$setting);					
					$singalData['principal_identity'][] = $this->appid;
					$singalData['indicatrix_identity'][] = $this->appid;
					$singalData['id'][] = $this->dataId;
					$singalData['data'][] = $ema;
					$singalData['cycle'][] = $this->appid;
					$singalData['dateline'][] = $curTime;
					$singalData['lastupdate'][] = $curTime;
					break;
				case 'wma':
					$wma = $wmaobj->setData($this->dataId,$close,$setting)->get();					
					$singalData['principal_identity'][] = $this->appid;
					$singalData['indicatrix_identity'][] = $this->appid;
					$singalData['id'][] = $this->dataId;
					$singalData['data'][] = $wma;
					$singalData['cycle'][] = $this->appid;
					$singalData['dateline'][] = $curTime;
					$singalData['lastupdate'][] = $curTime;
					break;
				case 'kdj':
					$kdj = $kdjObj->setSymbol($this->dataId)->kdj($setting,array($open,$high,$low,$close),$kdj);
				break;
			}
		}
		
		$this->model('QuotationSignal')->subtable($subTableName)->data($singalData)->replace();
		
	}
	
}