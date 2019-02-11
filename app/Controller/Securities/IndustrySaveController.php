<?php
/**
 *
 * 调账编辑
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
			'industryId'=>array('type'=>'digital','tooltip'=>'调账ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$industryId = $this->argument('industryId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($industryId){
			$this->service('SecuritiesIndustry')->update($setarr,$industryId);
		}else{
			
			if($this->service('SecuritiesIndustry')->checkIndustryTitle($setarr['title'])){
				
				$this->info('调账已存在',4001);
			}
			
			$this->service('SecuritiesIndustry')->insert($setarr);
		}
	}
}
?>