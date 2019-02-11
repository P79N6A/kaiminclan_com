<?php
/**
 *
 * 收藏检测
 *
 * 20180301
 *
 */
class CollectionCheckedController extends Controller {
	
	protected $permission = 'user';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'id'=>array('type'=>'digital','tooltip'=>'收藏ID'),
			'idtype'=>array('type'=>'digital','tooltip'=>'收藏类型'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$id = $this->argument('id');
		$idtype = $this->argument('idtype');
		
		if(!in_array($idtype,AuthorityCollectionModel::getIdtypeIds())){
			$this->info('未定义的收藏类型',30012);
		}
		
		$collectionData = $this->service('AuthorityCollection')->getCollectionByIdtypeIds($idtype,$id,$this->session('uid'));
		
		$this->assign('collection',$collectionData);
	}
}
?>