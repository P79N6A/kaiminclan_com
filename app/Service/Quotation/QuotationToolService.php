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
	protected $dataType;
	
	private $folder = '';
	
	private $period = array();
	
	protected $framework = array();
	
	protected $signal = array();
	
	protected $quotataion = array();
	
	protected $data = array();
	
	protected $curTime = 0;
	
	protected $debug = 0;
	
	//更新模式
	//1，替换，2，更新
	private $mode;
	
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
			$this->signal  = json_decode($principalData['signal'],true);
		}
		
		$this->mode = $principalData['mode'];
		
		
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
		switch($code){
			case 'period':
				$this->curTime = $value;
			break;
			default:
			if(isset($this->framework[$code])){
				
				$curTime = $this->getTime();
				$this->data['framework_identity'][] = $this->framework[$code];
				$this->data['id'][] = $this->dataId;
				if(!is_numeric($value)){
					$value = 0;
				}
				$this->data['data'][] = (empty($value)?0:$value);
				$this->data['cycle'][] = (date('Ymd',$curTime)).(1440);
				$this->data['dateline'][] = $curTime;
				$this->data['lastupdate'][] = $curTime;
			}
			break;
		}
		
	}
	
	private function getTable($period){
		$table = '';
		if(substr($this->curTime,0,4) != 1970){
			$this->curTime = date('Ymd');
		}
		if(!$this->debug){
				switch($period){
					case 1:
						$table = $this->folder.'_'.date('Ymdhi',strtotime($this->curTime));
					break;
					case 5:
						$week = ceil(((strtotime($this->curTime) - strtotime(date('Y').'-01-01 00:00:00')))/(7*86400));
						$table = $this->folder.'_'.date('Y').'_'.$week;
					break;
					case 15:
						$table = $this->folder.'_'.date('Ymdh',strtotime($this->curTime));
					break;
					case 30:
						$table = $this->folder.'_'.date('Ymdh',strtotime($this->curTime));
					break;
					case 60:
						$table = $this->folder.'_'.date('Ymd',strtotime($this->curTime));
					break;
					case 240:
						$table = $this->folder.'_'.date('Ym',strtotime($this->curTime));
					break;
					case 1440:
						$table = $this->folder.'_'.date('Y',strtotime($this->curTime));
					break;
					case 28800:
					case 345600:
						$table = $this->folder;
					break;
				}
		}
		return $table;
	}
	
	/***
	 *
	 * 分库
	 *
	 * 分表
	 *
	 * 数据的更新，有两种方式，替换，一个是修改，加法，减法
	 *
	 */
	
	
	public final function add()	
	{
		if(empty($this->data)){
			return -1;
		}
		
		foreach($this->period as $key=>$period){
			
			$cycle = $this->getRevolutionTime($period,$this->curTime);
		
			foreach($this->data['cycle'] as $cnt=>$_cycle){
				$this->data['cycle'][$cnt] = $cycle; 
			}
			switch($period){
				case 1:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 5:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 15:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 30:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 60:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 240:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 1440:
					$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
				break;
				case 28800:
				case 345600:
					$where = array(
						'cycle'=>$cycle,
						'framework_identity'=>$this->data['framework_identity'],
						'id'=>$this->dataId
					);
					
					
					$invaluableData = array();
					$invaluableList = $this->model('QuotationInvaluable')->subtable($this->getTable($period))->where($where)->select();
					if($invaluableList){
						foreach($invaluableList as $cnt=>$invaluable){
							$invaluableData[$invaluable['framework_identity']] = $invaluable['data'];
						}
					}
					if(!$invaluableData){
						//新建
						foreach($this->data['cycle'] as $cnt=>$cycle){
							$this->data['cycle'][$cnt] = $cycle; 
						}
						$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($this->data)->replace();
					}else{
						$invaluableNewData = array();
						$where = array();
						
						$invaluableNewData['lastupdate'] = $this->getTime();		
						$where['id'] = $this->dataId;
						
						foreach($this->data['framework_identity'] as $cnt=>$frameworkId){
							$newData = $this->data['data'][$cnt];
							if(!is_numeric($newData)){
								$newData = 0;
							}
							switch($this->mode){
								case 1:
									if($newData != $invaluableData[$frameworkId]){										
										$where['framework_identity'] = $frameworkId;
										$invaluableNewData['data'] = $newData;										
									}
									break;
								case 2:
									$invaluableNewData['data'] = $invaluableData[$frameworkId]+$newData;				
									break;
							}
							$this->model('QuotationInvaluable')->subtable($this->getTable($period))->data($invaluableNewData)->where($where)->save();
						}
					}
				break;
			}
			$this->refreshSignal($this->dataId,$period);
		}	
		
	}
	
	protected function refreshSignal($symbol,$period){
		
		require_once __ROOT__.'/vendor/PHPSignal/PHPDirection.php';
		require_once __ROOT__.'/vendor/PHPSignal/PHPOscillator.php';
		if(empty($this->signal)){
			return -1;
		}
		
		if(empty($this->quotataion)){
			return -2;
		}
		list($curTime,$open,$high,$low,$close) = $this->quotataion;
		
		$subTableName = $this->getTable($period);
		
		
		
		$kdjObj = new PHPOscillator(__STORAGE__);
		$wmaobj = new PHPDirection(__STORAGE__);
		
		$curTime = strtotime($curTime);
		$cycle = $this->getRevolutionTime($period,$curTime);
		
		
		$singalData = $oscillatorData = $directionData = array();
		foreach($this->signal as $index=>$setting){
			switch($index){
 				case 'oscillator':
					$_setting = array();
					foreach($setting as $key=>$data){
						$_setting[$data['code']] = $data['weight'];
					}
					$oscillatorData = $kdjObj->setSymbol($this->dataId)->kdj($_setting,array('cycle'=>$cycle,'open'=>$open,'high'=>$high,'low'=>$low,'close'=>$close));
					
					foreach($setting as $key=>$data){
						$singalData['principal_identity'][] = $this->appid;
						$singalData['structure_identity'][] = $data['id'];
						$singalData['id'][] = $this->dataId;
						$singalData['data'][] = isset($oscillatorData[$data['code']])?$oscillatorData[$data['code']]:0;
						$singalData['cycle'][] = $cycle;
						$singalData['dateline'][] = $curTime;
						$singalData['lastupdate'][] = $curTime;
					}
					
					
					break;
				case 'direction':
				
					$_setting = array();
					foreach($setting as $key=>$data){
						$_setting[$data['code']] = $data['weight'];
					}
					
					$directionData = $wmaobj->setData($this->dataId,array('cycle'=>$cycle,'close'=>$close),$_setting)->get();
					
					foreach($setting as $key=>$data){
						$singalData['principal_identity'][] = $this->appid;
						$singalData['structure_identity'][] = $data['id'];
						$singalData['id'][] = $this->dataId;
						$singalData['data'][] = isset($directionData[$data['code']])?$directionData[$data['code']]:0;
						$singalData['cycle'][] = $cycle;
						$singalData['dateline'][] = $curTime;
						$singalData['lastupdate'][] = $curTime;
					}
					
				break;
			}
		}
		
		
		$realtimeTime = $this->getTime();
		
		$opportunityData = array();		
		if($directionData['ema'] > $directionData['wma']){
			//多方
			if($oscillatorData['signal'] < 25){
				//开多仓
				$opportunityData['sn'][] = $this->get_sn();
				$opportunityData['idtype'][] = $this->dataType;
				$opportunityData['id'][] = $this->dataId;
				$opportunityData['cycle'][] = $cycle;
				$opportunityData['style'][] = 1;
				$opportunityData['univalent'][] = $open;
				$opportunityData['dateline'][] = $realtimeTime;
				$opportunityData['lastupdate'][] = $realtimeTime;
			}
			if($oscillatorData['signal'] > 80){
				//平仓
				$opportunityData['sn'][] = $this->get_sn();
				$opportunityData['idtype'][] = $this->dataType;
				$opportunityData['id'][] = $this->dataId;
				$opportunityData['cycle'][] = $cycle;
				$opportunityData['style'][] = 2;
				$opportunityData['univalent'][] = $open;
				$opportunityData['dateline'][] = $realtimeTime;
				$opportunityData['lastupdate'][] = $realtimeTime;
			}
		}else{
			//空方
			if($oscillatorData['signal'] < 25){
				//平仓
				$opportunityData['sn'][] = $this->get_sn();
				$opportunityData['idtype'][] = $this->dataType;
				$opportunityData['id'][] = $this->dataId;
				$opportunityData['cycle'][] = $cycle;
				$opportunityData['univalent'][] = $open;
				$opportunityData['style'][] = 3;
				$opportunityData['dateline'][] = $realtimeTime;
				$opportunityData['lastupdate'][] = $realtimeTime;
			}
			if($oscillatorData['signal'] > 80){
				//开空仓
				$opportunityData['sn'][] = $this->get_sn();
				$opportunityData['idtype'][] = $this->dataType;
				$opportunityData['id'][] = $this->dataId;
				$opportunityData['cycle'][] = $cycle;
				$opportunityData['style'][] = 4;
				$opportunityData['univalent'][] = $open;
				$opportunityData['dateline'][] = $realtimeTime;
				$opportunityData['lastupdate'][] = $realtimeTime;
			}
		}
		
		if(!empty($opportunityData)){
			$this->model('QuotationOpportunity')->subtable($subTableName)->data($opportunityData)->addMulti();		
		}
		$this->model('QuotationSignal')->subtable($subTableName)->data($singalData)->replace();
		
	}
	
	/**
	 *
	 * 提取时间线
	 * 根据指定时间，转换数据报表里的时间线
	 * 
	 * @param $cycle 日期
	 * @param $dateline 日期　默认取当天
	 * @return int 
	 */
	 public function getRevolutionTime($cycle,$dateline = 0){
		 $revolution = 0;
		 $currentDateline = time(); 
		 if($dateline){
			 $currentDateline = $dateline;
			 if(!is_numeric($dateline)){
				 $currentDateline = strtotime($dateline);
			 }
		 }
		 
		 switch($cycle){
			case 1: 
				$revolution = date('YmdHi',$currentDateline).'01';
				break;
			case 5: 
				$minute = sprintf('%02d',ceil(date('i',$currentDateline)/5)); 
				$revolution = date('YmdH',$currentDateline).$minute.'05'; 
				break;
			case 15: 
				$minute = sprintf('%02d',ceil(date('i',$currentDateline)/15)); 
				$revolution = date('YmdH',$currentDateline).$minute.'15'; 
				break;
			case 30: 
				$minute = ceil(date('i',$currentDateline)/30); 
				$revolution = date('YmdH',$currentDateline).$minute.'030'; 
				break;
			case 60: 
				$revolution = date('YmdH',$currentDateline).'0060'; 
				break;
			case 1440: 
				$revolution = date('Ymd',$currentDateline).'001440'; 
				break;
			case 7200: 
				$revolution = date('Y',$currentDateline).sprintf('%02d',date('W',$currentDateline)).'00007200'; 
				break;
			case 28800: 
				$revolution = date('Ym',$currentDateline).'00000288'; 
				break;
			case 86400: 
				$season = sprintf('%02d',ceil(date('n',$currentDateline)/3)); 
				$revolution = date('Y',$currentDateline).$season.'00000864';
				break;
			case 345600: 
				$revolution = date('Y',$currentDateline).'0000003456'; 
				break;
		}
		return $revolution;
	 }
	
}