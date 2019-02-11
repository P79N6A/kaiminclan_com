<?php
/**
 *
 * 信号编辑
 *
 * 20180301
 *
 */
class SignalSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'superviseId'=>array('type'=>'digital','tooltip'=>'信号ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$superviseId = $this->argument('superviseId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($superviseId){
			$this->service('QuotationSignal')->update($setarr,$superviseId);
		}else{
			if($this->service('QuotationSignal')->checkSignal($setarr['title'])){
				$this->info('此信号机构已存在',40012);
			}
			$this->service('QuotationSignal')->insert($setarr);
		}
	}
}
?>