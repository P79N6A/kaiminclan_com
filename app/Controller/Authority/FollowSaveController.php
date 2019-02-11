<?php
/**
 *
 * 关注
 *
 * 20180301
 *
 */
class FollowSaveController extends Controller {
	
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
		
		$followId = $this->service('AuthorityFollow')->checkFollow($id,$idtype,$this->session('uid'));
		if($followId){
			$this->service('AuthorityFollow')->removeFollowId($followId);
		}
		
		$followNewData = array(
			'idtype'=>$idtype,
			'id'=>$id
		);
		
		$this->service('AuthorityFollow')->insert($followNewData);
		
	}
}
?>