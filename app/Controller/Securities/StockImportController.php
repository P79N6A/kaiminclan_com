<?php
/**
 *
 * 证券导入
 *
 * 20180301
 *
 */
class StockImportController extends Controller {
	
	protected $permission = 'realtime';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'exchangeId'=>array('type'=>'digital','tooltip'=>'交易所'),
			'attach'=>array('type'=>'string','tooltip'=>'文件','default'=>__STORAGE__.'\tmp\stock.xls'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$exchangeId = $this->argument('exchangeId');
		$file = $this->argument('attach');
		
		require_once __VENDOR__.'/extend/PHPExcel/Classes/PHPExcel.php';
		
		if(!is_file($file)){
			$this->info('文件不存在',400012);
		}
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$PHPExcel = $reader->load($file); // 载入excel文件
		
		$dataArray = $PHPExcel->getSheet(0)->toArray();
		if(!$dataArray){
			$this->info('没有数据',400013);
		}
		list($tableHeadData) = $dataArray;
		
		unset($dataArray[0]);
		
		
		
		$uid = $this->session('uid');
		$uid = intval($uid);
		$dateline = $this->getTime();
		$pageMultiList = array();
		foreach($dataArray as $key=>$page){
			list($symbol,$fullname) = $page;
			$status = 1;
			if(!$symbol){
				$status = 2;
				$where = array();
				$where['symbol'] = $symbol;
				$memData = $this->model('SecuritiesStock')->where($where)->find();
				if($memData){
					continue;
				}
			}
			$pageMultiList['exchange_identity'][] = $exchangeId;
			$pageMultiList['title'][] = $fullname;
			$pageMultiList['english'][] = $fullname;
			$pageMultiList['sn'][] = $this->get_sn();
			$pageMultiList['symbol'][] = $symbol;
			$pageMultiList['dateline'][] = $dateline;
			$pageMultiList['subscriber_identity'][] = $uid;
			$pageMultiList['lastupdate'][] = $dateline;
			$pageMultiList['status'][] = $status;
		}
		
		if($pageMultiList){
			$this->model('SecuritiesStock')->data($pageMultiList)->addMulti();
		}
		
		
		
	}
}
?>