<?php
/**
 *
 * 客户等级编辑
 *
 * 20180301
 *
 */
class TheaterSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'theaterId'=>array('type'=>'digital','tooltip'=>'客户等级ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$theaterId = $this->argument('theaterId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($theaterId){
			$this->service('PropertyTheater')->update($setarr,$theaterId);
		}else{
			
			if($this->service('PropertyTheater')->checkTheaterTitle($setarr['title'])){
				
				$this->info('战区已存在',4001);
			}
			
			$theaterId = $this->service('PropertyTheater')->insert($setarr);
		}
		
		$this->assign('theaterId',$theaterId);
	}
}
?>