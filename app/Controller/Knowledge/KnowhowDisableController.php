<?php
/**
 *
 * 禁用知识
 *
 * 20180301
 *
 */
class KnowhowDisableController extends Controller {
	
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
		
		$knowhowId = $this->argument('knowhowId');
		
		$knowhowInfo = $this->service('KnowledgeKnowhow')->getKnowhowInfo($knowhowId);
		if(!$knowhowInfo){
			$this->info('知识不存在',4101);
		}
		
		if($knowhowInfo['status'] == KnowledgeKnowhowModel::INTERCALATE_SUPERVISE_STATUS_ENABLE){
			$this->service('KnowledgeKnowhow')->update(array('status'=>KnowledgeKnowhowModel::INTERCALATE_SUPERVISE_STATUS_DISABLED),$knowhowId);
		}
	}
}
?>