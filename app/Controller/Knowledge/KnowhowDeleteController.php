<?php
/**
 *
 * 删除知识
 *
 * 20180301
 *
 */
class KnowhowDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'knowhowId'=>array('type'=>'digital','tooltip'=>'知识ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeKnowhowIds = $this->argument('knowhowId');
		
		$knowhowInfo = $this->service('KnowledgeKnowhow')->getKnowhowInfo($removeKnowhowIds);
		
		if(!$knowhowInfo){
			$this->info('知识不存在',4101);
		}
		
		$this->service('KnowledgeKnowhow')->removeKnowhowId($removeKnowhowIds);
		
		$sourceTotal = count($knowhowId);
		$successNum = count($removeKnowhowIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>