<?php
/**
 *
 * 公司编辑
 *
 * 20180301
 *
 */
class CapitalSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'capitalId'=>array('type'=>'digital','tooltip'=>'公司ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'主题'),
			'scale_identity'=>array('type'=>'digital','tooltip'=>'等级'),
			'capital_identity'=>array('type'=>'digital','tooltip'=>'隶属主体','default'=>0),
			'industry_identity'=>array('type'=>'digital','tooltip'=>'行业'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$capitalId = $this->argument('capitalId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'scale_identity' => $this->argument('scale_identity'),
			'capital_identity' => $this->argument('capital_identity'),
			'industry_identity' => $this->argument('industry_identity'),
			'remark' => $this->argument('remark'),
		);
		
		$this->model('PropertyCapital')->start();
		
		if($capitalId){
			$this->service('PropertyCapital')->update($setarr,$capitalId);
		}else{
			if($this->service('PropertyCapital')->checkCapitalTitle($setarr['title'])){
				$this->info('此公司已存在',400001);
			}
			$capitalId = $this->service('PropertyCapital')->insert($setarr);
		}
		$this->model('PropertyCapital')->commit();
		
		$this->assign('capitalId',$capitalId);
	}
}
?>