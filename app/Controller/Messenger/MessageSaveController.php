<?php
/**
 *
 * 栏目编辑
 *
 * 20180301
 *
 */
class MessageSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'messageId'=>array('type'=>'digital','tooltip'=>'栏目ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'template_identity'=>array('type'=>'digital','tooltip'=>'模板','default'=>0),
			'content'=>array('type'=>'string','tooltip'=>'内容','length'=>200),
			'recipient'=>array('type'=>'doc','tooltip'=>'接收人'),
			'status'=>array('type'=>'digital','tooltip'=>'状态'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$messageId = $this->argument('messageId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'template_identity' => $this->argument('template_identity'),
			'content' => $this->argument('content'),
			'recipient'=>$this->argument('recipient'),
			'status' => $this->argument('status'),
			'remark' => $this->argument('remark')
		);
		
		if($messageId){
			$this->service('MessengerMessage')->update($setarr,$messageId);
		}else{
			$this->service('MessengerMessage')->insert($setarr);
		}
	}
}
?>