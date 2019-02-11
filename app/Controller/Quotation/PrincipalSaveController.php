<?php
/**
 *
 * 科目编辑
 *
 * 20180301
 *
 */
class PrincipalSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'principalId'=>array('type'=>'digital','tooltip'=>'科目ID','default'=>0),
			'code'=>array('type'=>'letter','tooltip'=>'编码'),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'application_identity'=>array('type'=>'digital','tooltip'=>'应用'),
			'functional_identity'=>array('type'=>'digital','tooltip'=>'功能'),
			'period'=>array('type'=>'doc','tooltip'=>'周期'),
			'signal'=>array('type'=>'digital','tooltip'=>'指标'),
			'mode'=>array('type'=>'digital','tooltip'=>'模式'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$principalId = $this->argument('principalId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'application_identity' => $this->argument('application_identity'),
			'title' => $this->argument('title'),
			'period' => implode(',',$this->argument('period')),
			'signal' => $this->argument('signal'),
			'mode' => $this->argument('mode'),
			'remark' => $this->argument('remark')
		);
		
		if($principalId){
			$this->service('QuotationPrincipal')->update($setarr,$principalId);
		}else{
			
			$this->service('QuotationPrincipal')->insert($setarr);
		}
	}
}
?>