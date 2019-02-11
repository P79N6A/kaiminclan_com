<?php
/**
 *
 * 关注删除
 *
 * 20180301
 *
 */
class CollectionDeleteController extends Controller {
	
	protected $permission = 'user';
	
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'collectionId'=>array('type'=>'digital','tooltip'=>'收藏ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$collectionId = $this->argument('collectionId');
				
		$uid = intval($this->session('uid'));
		$collectionData = $this->service('AuthorityCollection')->getUserCollectionInfoById($collectionId,$uid);
		if(!$collectionData){
			$this->info('不存在的数据',41001);
		}
		
		$removeIds = array();
		foreach($collectionData as $key=>$collection){
			if($collection['subscriber_identity'] != $uid) continue;
			$removeIds[] = $collection['identity'];
		}
		
		$this->service('AuthorityCollection')->removeCollectionId($removeIds);
		
		$this->assign('successNum',count($removeIds));
		$this->assign('failedNum',count($collectionId)-count($removeIds));
	}
}
?>