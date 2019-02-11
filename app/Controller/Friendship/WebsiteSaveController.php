<?php
/**
 *
 * 科目编辑
 *
 * 20180301
 *
 */
class WebsiteSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'websiteId'=>array('type'=>'digital','tooltip'=>'科目ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'doc','tooltip'=>'情况说明'),
			'classify_identity'=>array('type'=>'digital','tooltip'=>'分类'),
			'link'=>array('type'=>'url','tooltip'=>'科目ID','default'=>0),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'附件','default'=>0),
			'start_date'=>array('type'=>'date','format'=>'datetime','tooltip'=>'开始日期'),
			'stop_date'=>array('type'=>'date','format'=>'datetime','tooltip'=>'结束日期'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$websiteId = $this->argument('websiteId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'classify_identity' => $this->argument('classify_identity'),
			'link' => $this->argument('link'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'start_date' => $this->argument('start_date'),
			'stop_date' => $this->argument('stop_date'),
			'remark' => $this->argument('remark')
		);
		
		if($websiteId){
			$this->service('FriendshipWebsite')->update($setarr,$websiteId);
		}else{
			
			if($this->service('FriendshipWebsite')->checkWebsiteTitle($setarr['title'])){
				
				$this->info('科目已存在',4001);
			}
			
			$this->service('FriendshipWebsite')->insert($setarr);
		}
	}
}
?>