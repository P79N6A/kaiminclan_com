<?php
/**
 *
 * 债务编辑
 *
 * 20180301
 *
 */
class IndebtednessSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'indebtednessId'=>array('type'=>'digital','tooltip'=>'债务ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
			'content'=>array('type'=>'string','tooltip'=>'介绍','default'=>''),
			'credit_identity'=>array('type'=>'digital','tooltip'=>'借款机构'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
			'currency_identity'=>array('type'=>'digital','tooltip'=>'货币'),
			'account_identity'=>array('type'=>'digital','tooltip'=>'账户'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'start_date'=>array('type'=>'date','format'=>'dateline','tooltip'=>'开始时间'),
			'deadline'=>array('type'=>'digital','tooltip'=>'期限'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$indebtednessId = $this->argument('indebtednessId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'credit_identity' => $this->argument('credit_identity'),
			'subject_identity' => $this->argument('subject_identity'),
			'currency_identity' => $this->argument('currency_identity'),
			'account_identity' => $this->argument('account_identity'),
			'amount' => $this->argument('amount'),
			'start_date' => $this->argument('start_date'),
			'deadline' => $this->argument('deadline'),
			'remark' => $this->argument('remark'),
		);
		
		list($setarr['subject_identity']) = $setarr['subject_identity'];
		$setarr['stop_date'] = $setarr['start_date']+(60*60*24*30*$setarr['deadline']);
		
		$this->model('PermanentCredit')->start();
		if($indebtednessId){
			$this->service('PermanentIndebtedness')->update($setarr,$indebtednessId);
		}else{
			$indebtednessId = $this->service('PermanentIndebtedness')->insert($setarr);
		}
		
		
		$where = array();
		$where['id'] = $indebtednessId;
		$where['idtype'] = PermanentPayableModel::PERMANENT_PAYABLE_IDTYPE_INDEBTEDNESS;
		
		$count = $this->model('PermanentPayable')->where($where)->count();
		if($count){
			$this->model('PermanentPayable')->where($where)->delete();
		}
		$creditData = $this->service('PermanentCredit')->getCreditInfo($setarr['credit_identity']);
		if(!$creditData){
			$this->model('PermanentCredit')->rollback();
			$this->info('未定义借款机构',40012);
		}
		
		
		$creditData = current($creditData);
		if($creditData['checkout'] < 1){
			$this->model('PermanentCredit')->rollback();
			$this->info('未定义结账日期',40013);
		}
		$startTime = $setarr['start_date'];
		$deadline = $setarr['deadline'];
			
		$payableData = array();
		$totalInterest = 0;
		
		list($amount,$interest) = $this->getInterestAmount($setarr['amount'],$deadline,$creditData['interest']);
		
		for($cnt=1;$cnt<=$deadline;$cnt++){
			$lastMonthTime = $startTime+(60*60*24*30*$cnt);
			
			$payableData['id'][] = $indebtednessId;
			$payableData['idtype'][] = PermanentPayableModel::PERMANENT_PAYABLE_IDTYPE_INDEBTEDNESS;
			$payableData['title'][] = $setarr['title'].'本息'.$cnt.'期';
			$payableData['amount'][] = $amount;
			
			$payableData['interest'][] = $interest;
			$payableData['expire_date'][] = strtotime(date('Y-m',$lastMonthTime).'-'.$creditData['checkout']);
		}
		
		
		if(count($payableData) > 0){
			$this->service('PermanentPayable')->insert($payableData,1);
		}
		$this->model('PermanentCredit')->commit();
	}
	
	/**
	 *
	 * @param $amount 总额
	 * @param $deedline 月
	 * @param $interest 月利率
	 */
	public function getInterestAmount($amount,$deedline,$interest){
		$monthAmount = round($amount/$deedline,2);
		//月利率
		$interest = $interest/100*365/12;
		
		
		//已还金额
		$descriptAmount = 0;
		$interestTotalAmount = 0;
		$totalAmount = 0;
		$curAmount = 0;
		//等额本金
		for($i=1; $i<=$deedline;$i++){
			
			$interestAmount = round(($amount-$descriptAmount)*$interest,2);
			$curAmount = ($monthAmount+$interestAmount);
			
			$descriptAmount += $monthAmount;
			
			$totalAmount+= $monthAmount+$interestAmount;
		}
		return array(round($totalAmount/$deedline,2),$interestTotalAmount);
	}
}
?>