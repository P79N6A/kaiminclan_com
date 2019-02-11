<?php
/**
 *
 * 接口编辑
 *
 * 20180301
 *
 */
class JoggleSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'joggleId'=>array('type'=>'digital','tooltip'=>'接口ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'项目','default'=>0),
			'introduce'=>array('type'=>'doc','tooltip'=>'输入参数'),
			'outcome'=>array('type'=>'doc','tooltip'=>'输出参数'),
			'method'=>array('type'=>'digital','tooltip'=>'访问方式'),
			'logic_attach_id'=>array('type'=>'digital','tooltip'=>'设计图','default'=>0),
			'finally_attach_id'=>array('type'=>'digital','tooltip'=>'响应图','default'=>0),
			'address'=>array('type'=>'string','tooltip'=>'接口地址'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>1),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$joggleId = $this->argument('joggleId');
		
		$setarr = array(
			'title'=>$this->argument('title'),
			'subject_identity'=>$this->argument('subject_identity'),
			'method'=>$this->argument('method'),
			'logic_attach_id'=>$this->argument('logic_attach_id'),
			'finally_attach_id'=>$this->argument('finally_attach_id'),
			'introduce'=>$this->argument('introduce'),
			'outcome'=>$this->argument('outcome'),
			'address'=>$this->argument('address'),
			'status'=>$this->argument('status'),
			'content'=>$this->argument('content'),
			'remark'=>$this->argument('remark')
		);
		
		if($joggleId){
			$this->service('FabricationJoggle')->update($setarr,$joggleId);
		}else{
			
			if($this->service('FabricationJoggle')->checkJoggleTitle($setarr['title'],$setarr['prototype_identity'])){
				
				$this->info('接口已存在',4001);
			}
			
			$this->service('FabricationJoggle')->insert($setarr);
		}
	}
}
?>