<?php
/**
 *
 * 资源列表
 *
 * 20180301
 *
 */
class catalogueListController extends Controller {
	
	protected $permission = 'admin';
	
	protected $method = 'get';
	
	protected $accept = 'application/json';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'kw'=>array('type'=>'string','tooltip'=>'关键字','default'=>''),
			'start'=>array('type'=>'digital','tooltip'=>'开始页','default'=>1),
			'perpage'=>array('type'=>'digital','tooltip'=>'每页数量','default'=>20),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$keyword = $this->argument('kw');
		$start = $this->argument('start');
		$perpage = $this->argument('perpage');
		
		if($keyword){
			$where['title'] = array('like','%'.$keyword.'%');
		}
		
		$count = $this->model('ResourcesCatalog')->where($where)->count();
		
		$listdata = array();
		
		if($count){
			
			$listdata = $this->model('ResourcesCatalog')->where($where)->limit($start,$perpage,$count)->select();
			
			$subscriberIds = array();
			foreach($listdata as $key=>$group){
				$subscriberIds[] = $group['subscriber_identity'];
				$listdata[$key]['subscriber'] = array(
					'identity'=>$group['subscriber_identity'],
					'fullname'=>''
				);
			}
		}
		
		
		$this->assign('total',$total);
		$this->assign('start',$start);
		$this->assign('perpage',$perpage);
		$this->assign('listdata',$listdata);
	}
}
?>