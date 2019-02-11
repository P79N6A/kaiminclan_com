<?php
/**
 *
 * 监管编辑
 *
 * 20180301
 *
 */
class SuperviseSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'superviseId'=>array('type'=>'digital','tooltip'=>'监管ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$superviseId = $this->argument('superviseId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($superviseId){
			$this->service('IntercalateSupervise')->update($setarr,$superviseId);
		}else{
			if($this->service('IntercalateSupervise')->checkSupervise($setarr['title'])){
				$this->info('此监管机构已存在',40012);
			}
			$this->service('IntercalateSupervise')->insert($setarr);
		}
	}
}
?>