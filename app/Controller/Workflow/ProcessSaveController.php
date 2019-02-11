<?php
/**
 *
 * 流程编辑
 *
 * 20180301
 *
 */
class ProcessSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'processId'=>array('type'=>'digital','tooltip'=>'流程ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'效果图','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'流程状态','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$processId = $this->argument('processId');
		
		$setarr = array(
			'attachment_identity' => $this->argument('attachment_identity'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
		if($processId){
			$this->service('WorkflowProcess')->update($setarr,$processId);
		}else{
			
			if($this->service('WorkflowProcess')->checkProcessTitle($title)){
				
				$this->info('流程已存在',4001);
			}
			
			$this->service('WorkflowProcess')->insert($setarr);
		}
	}
}
?>