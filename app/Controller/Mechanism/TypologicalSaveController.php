<?php
/**
 *
 * 账户类型编辑
 *
 * 20180301
 *
 */
class TypologicalSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'typologicalId'=>array('type'=>'digital','tooltip'=>'账户类型ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$typologicalId = $this->argument('typologicalId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($typologicalId){
			$this->service('MechanismTypological')->update($setarr,$typologicalId);
		}else{
			
			if($this->service('MechanismTypological')->checkTypologicalTitle($setarr['title'])){
				
				$this->info('账户类型已存在',4001);
			}
			
			$this->service('MechanismTypological')->insert($setarr);
		}
	}
}
?>