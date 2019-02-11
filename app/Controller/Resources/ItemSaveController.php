<?php
/**
 *
 * 条目编辑
 *
 * 20180301
 *
 */
class ItemSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'itemId'=>array('type'=>'digital','tooltip'=>'条目ID','default'=>0),
			'page_identity'=>array('type'=>'digital','tooltip'=>'页面ID'),
			'item_identity'=>array('type'=>'digital','tooltip'=>'模块ID'),
			'id'=>array('type'=>'digital','tooltip'=>'数据ID','default'=>0),
			'idtype'=>array('type'=>'digital','tooltip'=>'数据类型','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'summary'=>array('type'=>'string','tooltip'=>'介绍','length'=>200),
			'fields'=>array('type'=>'string','tooltip'=>'字段','default'=>''),
			'link'=>array('type'=>'url','tooltip'=>'链接地址','default'=>''),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'附件','default'=>0),
			'start_time'=>array('type'=>'date','format'=>'dateline','tooltip'=>'开始时间','default'=>0),
			'stop_time'=>array('type'=>'date','format'=>'dateline','tooltip'=>'结束时间','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'条目状态','default'=>PaginationItemModel::PAGINATION_ITEM_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$itemId = $this->argument('itemId');
		
		$setarr = array(
			'page_identity' => $this->argument('page_identity'),
			'item_identity' => $this->argument('item_identity'),
			'id' => $this->argument('id'),
			'idtype' => $this->argument('idtype'),
			'title' => $this->argument('title'),
			'summary' => $this->argument('summary'),
			'start_time' => $this->argument('start_time'),
			'stop_time' => $this->argument('stop_time'),
			'fields' => json_encode($this->argument('fields')),
			'link' => $this->argument('link'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'status' => $this->argument('status')
		);
		
		if($itemId){
			$this->service('PaginationItem')->update($setarr,$itemId);
		}else{
			
			if($this->service('PaginationItem')->checkTitle($title)){
				
				$this->info('条目已存在',4001);
			}
			
			$this->service('PaginationItem')->insert($setarr);
		}
	}
}
?>