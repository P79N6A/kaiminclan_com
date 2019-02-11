<?php
/**
 *
 * 关注检测
 *
 * 20180301
 *
 */
class FollowCheckedController extends Controller {
	
	protected $permission = 'user';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'id'=>array('type'=>'digital','tooltip'=>'关注ID'),
			'idtype'=>array('type'=>'digital','tooltip'=>'关注类型'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$id = $this->argument('id');
		$idtype = $this->argument('idtype');
		
		if(!in_array($idtype,AuthorityFollowModel::getIdtypeIds())){
			$this->info('未定义的关注类型',30012);
		}
		
		$followData = $this->service('AuthorityFollow')->getFollowByIdtypeIds($id,$idtype,$this->session('uid'));
		
		$this->assign('follow',$followData);
	}
}
?>