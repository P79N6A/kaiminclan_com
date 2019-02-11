<?php
/**
 *
 * 足迹追踪
 *
 * 20180301
 *
 */
class CollectionCheckedController extends Controller {
	
	protected $permission = 'public';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'id'=>array('type'=>'digital','tooltip'=>'数据ID'),
			'idtype'=>array('type'=>'digital','tooltip'=>'数据类型'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$id = $this->argument('id');
		$idtype = $this->argument('idtype');
		
	}
}
?>