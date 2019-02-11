<?php
/**
 *
 * 关注删除
 *
 * 20180301
 *
 */
class FollowDeleteController extends Controller {
	
	protected $permission = 'user';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'followId'=>array('type'=>'digital','tooltip'=>'关注ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$followId = $this->argument('followId');
				
		$uid = $this->sesion('uid');
		$followData = $this->service('AuthorityFollow')->getUserFollowInfoById($followId,$uid);
		if(!$followData){
			$this->info('不存在的数据',41001);
		}
		
		$removeIds = array();
		foreach($followData as $key=>$follow){
			if($follow['subscriber_identity'] != $uid) continue;
			$removeIds[] = $follow['identity'];
		}
		
		$this->service('AuthorityFollow')->removeFollowId($removeIds);
		
		$this->assign('successNum',count($removeIds));
		$this->assign('failedNum',count($followId)-count($removeIds));
	}
}
?>