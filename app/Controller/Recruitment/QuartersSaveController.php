<?php
/**
 *
 * 岗位编辑
 *
 * 20180301
 *
 */
class QuartersSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quartersId'=>array('type'=>'digital','tooltip'=>'岗位ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'start_date'=>array('type'=>'date','tooltip'=>'开始时间'),
			'stop_date'=>array('type'=>'date','tooltip'=>'结束时间'),
			'quantity'=>array('type'=>'digital','tooltip'=>'人数'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$quartersId = $this->argument('quartersId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'start_date' => $this->argument('start_date'),
			'stop_date' => $this->argument('stop_date'),
			'quantity' => $this->argument('quantity'),
			'remark' => $this->argument('remark')
		);
		
		if($quartersId){
			$this->service('RecruitmentQuarters')->update($setarr,$quartersId);
		}else{
			
			if($this->service('RecruitmentQuarters')->checkQuartersTitle($setarr['title'])){
				
				$this->info('岗位已存在',4001);
			}
			
			$quartersId = $this->service('RecruitmentQuarters')->insert($setarr);
		}
		
		$this->assign('quartersId',$quartersId);
	}
}
?>