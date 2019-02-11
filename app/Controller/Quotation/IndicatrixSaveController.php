<?php
/**
 *
 * 科目编辑
 *
 * 20180301
 *
 */
class IndicatrixSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'indicatrixId'=>array('type'=>'digital','tooltip'=>'科目ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$indicatrixId = $this->argument('indicatrixId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($indicatrixId){
			$this->service('QuotationIndicatrix')->update($setarr,$indicatrixId);
		}else{
			
			$this->service('QuotationIndicatrix')->insert($setarr);
		}
	}
}
?>