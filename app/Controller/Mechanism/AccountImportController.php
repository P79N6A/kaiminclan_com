<?php
/**
 *
 * 账户导入
 *
 * 机构
 *
 */
class AccountImportController extends Controller {
	
	protected $permission = 'realtime';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'typologicalId'=>array('type'=>'digital','tooltip'=>'账户分类'),
			'attach'=>array('type'=>'string','tooltip'=>'文件','default'=>__STORAGE__.'\tmp\account.xls'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$typologicalId = $this->argument('typologicalId');
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
		$accountMultiList = array();
		foreach($dataArray as $key=>$account){
			list($title,$bank,$code,$amount,$remark) = $account;
			
			$accountMultiList['title'][] = $title;
			$accountMultiList['code'][] = $code;
			$accountMultiList['bank_identity'][] = $bank;
			$accountMultiList['typological_identity'][] = $typologicalId;
			$accountMultiList['remark'][] = empty($remark)?'未明确':$remark;
			$accountMultiList['dateline'][] = $dateline;
			$accountMultiList['subscriber_identity'][] = $uid;
			$accountMultiList['lastupdate'][] = $dateline;
		}
		
		if($accountMultiList){
			$this->model('MechanismAccount')->data($accountMultiList)->addMulti();
		}
		
		
		
	}
}
?>