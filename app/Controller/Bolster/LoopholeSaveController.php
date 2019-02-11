<?php
/**
 *
 * 漏洞编辑
 *
 * 20180301
 *
 */
class LoopholeSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'loopholeId'=>array('type'=>'digital','tooltip'=>'漏洞ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'weight'=>array('type'=>'digital','tooltip'=>'危害等级'),
			'factory_identity'=>array('type'=>'digital','tooltip'=>'厂商'),
			'software_identity'=>array('type'=>'digital','tooltip'=>'软件'),
			'style'=>array('type'=>'digital','tooltip'=>'类型'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'reference'=>array('type'=>'doc','tooltip'=>'参考网址','default'=>''),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>1),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$loopholeId = $this->argument('loopholeId');
		
		$setarr = array(
			'title'=>$this->argument('title'),
			'weight'=>$this->argument('weight'),
			'factory_identity'=>$this->argument('factory_identity'),
			'software_identity'=>$this->argument('software_identity'),
			'style'=>$this->argument('style'),
			'content'=>$this->argument('content'),
			'reference'=>json_encode($this->argument('reference'),json_unescaped_unicode),
			'status'=>$this->argument('status'),
			'remark'=>$this->argument('remark')
		);
		
		if($loopholeId){
			$this->service('BolsterLoophole')->update($setarr,$loopholeId);
		}else{
			
			if($this->service('BolsterLoophole')->checkLoopholeTitle($setarr['title'],$setarr['prototype_identity'])){
				
				$this->info('漏洞已存在',4001);
			}
			
			$this->service('BolsterLoophole')->insert($setarr);
		}
	}
}
?>