<?php
/**
 *
 * 债权编辑
 *
 * 20180301
 *
 */
class ObligatorySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'obligatoryId'=>array('type'=>'digital','tooltip'=>'债权ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
			'content'=>array('type'=>'string','tooltip'=>'介绍','default'=>''),
			'credit_identity'=>array('type'=>'digital','tooltip'=>'借款机构'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'科目'),
			'currenc_identity'=>array('type'=>'digital','tooltip'=>'货币'),
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
		$obligatoryId = $this->argument('obligatoryId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'credit_identity' => $this->argument('credit_identity'),
			'subject_identity' => $this->argument('subject_identity'),
			'amount' => $this->argument('amount'),
			'start_date' => $this->argument('start_date'),
			'deadline' => $this->argument('deadline'),
			'remark' => $this->argument('remark'),
		);
		
		$this->model('InvestmentReceivable')->start();
		
		if($obligatoryId){
			$this->service('InvestmentObligatory')->update($setarr,$obligatoryId);
		}else{
			
			
			$obligatoryId = $this->service('InvestmentObligatory')->insert($setarr);
		}
		
		$creditData = $this->service('PermanentCredit')->getCreditInfo($setarr['credit_identity']);
		if(!$creditData){
			$this->model('InvestmentReceivable')->rollback();
			$this->info('未定义借款机构',40012);
		}
		
		$creditData = current($creditData);
		if($creditData['checkout'] < 1){
			$this->model('InvestmentReceivable')->rollback();
			$this->info('未定义结账日期',40013);
		}
		$startTime = $setarr['start_date'];
		$deadline = $setarr['deadline'];
			
		$payableData = array();
		for($cnt=1;$cnt<$deadline;$cnt++){
			$lastMonthTime = $startTime+(60*60*24*30*$cnt);
			
			$interest = $setarr['amount']*$creditData['interest']/100*30;
			
			$payableData['id'][] = $obligatoryId;
			$payableData['idtype'][] = InvestmentReceivableModel::INVETMENT_RECEIVABLE_IDTYPE_OBLIGATORY;
			$payableData['title'][] = $setarr['title'].'本息'.$cnt.'期';
			$payableData['amount'][] = $setarr['amount']/$deadline+$interest;
			$payableData['expire_date'][] = strtotime(date('Y-m',$lastMonthTime).'-'.$creditData['checkout']);
		}
		
		if(count($payableData) > 0){
			$this->service('InvestmentReceivable')->insert($payableData,1);
		}
		$this->model('InvestmentReceivable')->commit();
	}
}
?>