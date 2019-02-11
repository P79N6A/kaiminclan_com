<?php
/**
 *
 * 通达信交易流水导入
 *
 */
class TradeTdxTakeNoteConsole extends Console {
	protected $debug = 1;
	public function fire(){
		
		foreach(array('3','6','9') as $key=>$month){
		
		$file = __DATA__.'/china-trader/20180'.$month.'31.xls';
		
		$fileData = $this->loadExcelData($file);
		
		if(!$fileData){
			$this->success('没有数据');
		}
		
		unset($fileData[0]);
		
		$accountId = $symbolId = 0;
		foreach($fileData as $key=>$data){
			//发生日期,成交时间,业务名称,证券代码,证券名称,成交价格,成交数量,成交金额,股份余额,手续费,印花税,过户费,其他费,发生金额,资金本次余额,委托编号,委托价格,委托数量,股东代码,资金帐号,币种
			list($today,$time,$subject,$symbol,$title,$univalent,$quantity,$amount,$aAmount,$shouxu,$yinhua,$guohu,$other,$happenAmount,$totalAmount,$code,$close,$weituoNo,$stockCode,$accountSn) = $data;
			$this->info($today.'>>'.$subject);
			
			$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle($subject);
			
			$accountData = $this->service('BankrollAccount')->getStockInfoByCode($accountSn);
			if(!$accountData){
				die();
				$this->finish();
			}
			$accountId = $accountData['identity'];
			
			$symbolData = $this->service('SecuritiesStock')->getStockInfoBySymbol($symbol);
			if($symbolData){
				$symbolId = $symbolData['identity'];
			}
			
			$curTime = strtotime($today.' '.$time);
			if(strpos($happenAmount,'-') !== false){
				$happenAmount = substr($happenAmount,1);
			}
			
			if(strpos($subject,'配股缴款') !== false){
			}
			
			//开仓
			if(strpos($subject,'买入') !== false){
				$this->service('PositionPurchase')->insert(array(
					'happen_date'=>$curTime,
					'account_identity'=>$accountId,
					'id'=>$symbolId,
					'idtype'=>PositionPurchaseModel::POSITION_PURCHASE_IDTYPE_STOCK,
					'univalent'=>$univalent,
					'quantity'=>$quantity,
					'code'=>$code,
				));
				//手续费
				if($shouxu > 0){
					
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('手续费');
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$shouxu
					));
				}
				//印花税
				if($yinhua > 0){
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('印花税');
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$yinhua
					));
				}
				//过户费
				if($guohu > 0){
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('过户费');
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$guohu
					));
				}
				//其他费
				if($other > 0){
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('其他费');
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$other
					));
				}
				
				
			}
			//平仓
			if(strpos($subject,'卖出') !== false){
				$loss = $profit = 0;
				$purchaseData  = $this->service('PositionPurchase')->getPurchaseByLast($accountId,$symbolId);
				//var_dump($purchaseData,$accountId,$symbolId); die();
				if($purchaseData){
					
					$cost = 0;
					$closeQuantity = $quantity;
					$inventory = 0;
					foreach($purchaseData as $cnt=>$purchase){
						if($closeQuantity <= 0){
							break;
						}
						$inventory += $purchase['quantity'];
						$closeQuantity = $closeQuantity-$purchase['quantity'];
						
						$cost += $purchase['univalent']*$purchase['quantity'];
						$this->service('PositionPurchase')->close($purchase['identity']);
					}
					if($cost > 0 && $inventory == $quantity){
						if($happenAmount > $cost){
							$profit = $happenAmount-$cost;
						}else{
							$loss = $cost-$happenAmount;
						}
					}
				}
				
				$this->service('PositionShipments')->insert(array(
					'happen_date'=>$curTime,
					'account_identity'=>$accountId,
					'purchase_identity'=>$purchaseData['identity'],
					'univalent'=>$univalent,
					'quantity'=>$quantity,
					'profit'=>$profit,
					'code'=>$code,
				));
				
				if($profit > 0){		
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('证券差价-盈利');
					$this->service('BankrollRevenue')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$profit
					));
				}
				if($loss > 0){		
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('证券差价-亏损');
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$loss
					));
				}
				
				//手续费
				if($shouxu > 0){
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$shouxu
					));
				}
				//印花税
				if($yinhua > 0){
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$yinhua
					));
				}
				//过户费
				if($guohu > 0){
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$guohu
					));
				}
				//其他费
				if($other > 0){
					$this->service('BankrollExpenses')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$other
					));
				}
			}
			
			//转账
			if(strpos($subject,'转出') !== false){

				$this->service('BankrollExpenses')->insert(array(
					'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
					'account_identity'=>$accountId,
					'amount'=>$happenAmount
				));
			}
			if(strpos($subject,'转入') !== false){
				
				$this->service('BankrollRevenue')->insert(array(
					'happen_date'=>$curTime,
					'subject_identity'=>$subjectId,
					'account_identity'=>$accountId,
					'amount'=>$happenAmount
				));
			}
			if(strpos($subject,'所得税') !== false){

				$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('所得税');
				$this->service('BankrollExpenses')->insert(array(
					'happen_date'=>$curTime,
					'subject_identity'=>$subjectId,
					'account_identity'=>$accountId,
					'amount'=>$happenAmount
				));
			}
			
			
			
			//分红
			if(strpos($subject,'红股') !== false){
				
				if($quantity){
					//送股
				}
				
				if($amount){
					//现金				
					$subjectId = $this->service('BankrollSubject')->getSubjectIdByTitle('现金分红');
					$this->service('BankrollRevenue')->insert(array(
						'happen_date'=>$curTime,
						'subject_identity'=>$subjectId,
						'account_identity'=>$accountId,
						'amount'=>$amount
					));
				}
			}
			if(strpos($subject,'利息归本') !== false){
				/*
				$this->service('PositionShipments')->insert(array(
					'happen_date'=>$curTime,
					'account_identity'=>$accountId,
					'amount'=>$amount
				));
				*/
			}
		}
		}
		
	}
	
	protected function loadExcelData($filename){
				
		
		require_once __ROOT__.'/vendor/PHPExcel/Classes/PHPExcel.php';
		
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPExcel = $reader->load($filename); // 载入excel文件
		
		return $PHPExcel->getSheet(0)->toArray();
	}
}