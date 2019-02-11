<?php
/**
 *
 * 信号
 *
 * 20180301
 *
 */
class SignalHistoricalController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$invaluableId = $this->argument('invaluableId');
		$invaluableId = $this->argument('invaluableId');
		
	}
}
?>