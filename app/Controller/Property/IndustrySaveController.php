<?php
/**
 *
 * 需求编辑
 *
 * 20180301
 *
 */
class IndustrySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'industryId'=>array('type'=>'digital','tooltip'=>'需求ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$industryId = $this->argument('industryId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
		);
		
		$this->model('PropertyIndustry')->start();
		
		if($industryId){
			$result = $this->service('PropertyIndustry')->update($setarr,$industryId);
			if($result < 0){
				$this->info('需求修改失败',400002);
			}
		}else{
			$industryId = $this->service('PropertyIndustry')->insert($setarr);
		}
		$this->model('PropertyIndustry')->commit();
		
		$this->assign('industryId',$industryId);
	}
}
?>