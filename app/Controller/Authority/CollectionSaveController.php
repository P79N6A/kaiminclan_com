<?php
/**
 *
 * 收藏
 *
 * 20180301
 *
 */
class CollectionSaveController extends Controller {
	
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
		
		if(!is_array($id)){
			$id = array($id);
		}
		
		$collectionList = $this->service('AuthorityCollection')->checkCollection($idtype,$id,$this->session('uid'));
		
		if($collectionList){
			
			$collectionIds = array();
			foreach($collectionList as $collectionId=>$oldId){
				$collectionIds[] = $collectionId;
				if(in_array($oldId,$id)){
					$cnt = array_search($oldId,$id);
						unset($id[$cnt]);
				}
			}
			$this->service('AuthorityCollection')->removeCollectionId($collectionIds);
		}
		
		$id = array_filter($id);
		if(count($id)){
			$this->service('AuthorityCollection')->insert($id,$idtype);
		}
	}
}
?>