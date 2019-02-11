<?php
/**
 *
 * 客户编辑
 *
 * 20180301
 *
 */
class ClienteteSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'clienteteId'=>array('type'=>'digital','tooltip'=>'客户ID','default'=>0),
			'fullname'=>array('type'=>'string','tooltip'=>'姓名'),
			'distinction_identity'=>array('type'=>'digital','tooltip'=>'会员等级'),
			'mobile'=>array('type'=>'mobile','tooltip'=>'手机号码'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$clienteteId = $this->argument('clienteteId');
		
		$setarr = array(
			'fullname' => $this->argument('fullname'),
			'distinction_identity' => $this->argument('distinction_identity'),
			'mobile' => $this->argument('mobile'),
			'remark' => $this->argument('remark'),
		);
		
		$this->model('CustomerClientete')->start();
		
		if($clienteteId){
			$result = $this->service('CustomerClientete')->update($setarr,$clienteteId);
			if($result < 0){
				$this->info('客户修改失败',400002);
			}
		}else{
			if($this->service('CustomerClientete')->checkClienteteMobile($setarr['mobile'])){
				$this->info('此客户已存在',400001);
			}
			$clienteteId = $this->service('CustomerClientete')->insert($setarr);
		}
		$this->model('CustomerClientete')->commit();
		
		$this->assign('clienteteId',$clienteteId);
	}
}
?>