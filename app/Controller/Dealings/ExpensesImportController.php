<?php
/**
 *
 * 导入支出
 *
 * 20180301
 *
 */
class ExpensesImportController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'accountId'=>array('type'=>'digital','tooltip'=>'账户'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$accountId = $this->argument('accountId');
		
		require_once __VENDOR__.'/extend/PHPExcel/Classes/PHPExcel.php';
		
		$file = 'D:\project\softwen_com\storage\tmp\xingyeyinhag.xls';
		if(!is_file($file)){
			$this->info('文件不存在',400012);
		}
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPExcel = $reader->load($file); // 载入excel文件
		
		$dataArray = $PHPExcel->getSheet(0)->toArray();
		if(!$dataArray){
			$this->info('没有数据',400013);
		}
		unset($dataArray[0]);
		
		
		$uid = $this->session('uid');
		$dateline = $this->getTime();
		$incomeData = $newData = array();
		foreach($dataArray as $key=>$data){
			list($traderDate,$recordDate,$title,$amount) = $data;
			$amount = str_replace('"','',$amount);
			$amount = str_replace(',','',$amount);
			$amount = str_replace(' ','',$amount);
			if(empty($amount)){
				continue;
			}
			if($amount < 0){
				$incomeData['happen_date'][] = strtotime($traderDate);
				$incomeData['title'][] = $title;
				$incomeData['amount'][] = $amount;
				$incomeData['account_identity'][] = $accountId;
				$incomeData['sn'][] = $this->get_sn();
				$incomeData['subscriber_identity'][] =$uid;
				$incomeData['dateline'][] = $dateline;					
				$incomeData['lastupdate'][] = $dateline;
			}else{
				$newData['happen_date'][] = strtotime($traderDate);
				$newData['title'][] = $title;
				$newData['account_identity'][] = $accountId;
				$newData['amount'][] = $amount;
				$newData['sn'][] = $this->get_sn();
				$newData['subscriber_identity'][] =$uid;
				$newData['dateline'][] = $dateline;					
				$newData['lastupdate'][] = $dateline;
			}
		}
		if($incomeData){
			$this->model('DealingsRevenue')->data($incomeData)->addMulti();
		}
		
		if($newData){
			$this->model('DealingsExpenses')->data($newData)->addMulti();
		}
		
		
		
	}
}
?>