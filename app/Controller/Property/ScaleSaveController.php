<?php
/**
 *
 * 客户编辑
 *
 * 20180301
 *
 */
class ScaleSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'scaleId'=>array('type'=>'digital','tooltip'=>'客户ID','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'编码'),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$scaleId = $this->argument('scaleId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
		);
		
		$this->model('PropertyScale')->start();
		
		if($scaleId){
			$result = $this->service('PropertyScale')->update($setarr,$scaleId);
			if($result < 0){
				$this->info('客户修改失败',400002);
			}
		}else{
			$scaleId = $this->service('PropertyScale')->insert($setarr);
		}
		$this->model('PropertyScale')->commit();
		
		$this->assign('scaleId',$scaleId);
	}
}
?>