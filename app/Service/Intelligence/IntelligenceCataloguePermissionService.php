<?php
/**
 *
 * 栏目
 *
 * 新闻
 *
 */
class IntelligenceCataloguePermissionServiceService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getCatalogueList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('IntelligenceCataloguePermission')->where($where)->count();
		if($count){
			$handle = $this->model('IntelligenceCataloguePermission')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getAllCatId($catalogueIds){
		
		$output = array(
			$catalogueIds
		);
		
		$where = array(
			'catalogue_identity'=>$catalogueIds,
			'status'=>0
		);
		
		$listdata = $this->model('IntelligenceCataloguePermission')->field('identity')->where($where)->select();
		
		if($listdata){
			foreach($listdata as $key=>$data){
				$output[] = $data['identity'];
			}
		}
		
		return $output;
		
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkCatalogueTitle($title,$catalogue_identity){
		if($title){
			$where = array(
				'title'=>$title,
				'catalogue_identity'=>$catalogue_identity
			);
			return $this->model('IntelligenceCataloguePermission')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $catalogueId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogueInfo($catalogueId,$field = '*'){
		$catalogData = array();
		
		if(!is_array($catalogueId)){
			$catalogueId = array($catalogueId);
		}
		
		$catalogueId = array_filter(array_map('intval',$catalogueId));
		
		if(!empty($catalogueId)){
		
			$where = array(
				'identity'=>$catalogueId
			);
			
			$catalogueData = $this->model('IntelligenceCataloguePermission')->field($field)->where($where)->select();
		}
		return $catalogueData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $catalogueId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogueId($catalogueId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('IntelligenceCataloguePermission')->where($where)->find();
		if($catalogueData){
			
			$output = $this->model('IntelligenceCataloguePermission')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $catalogueId 模块ID
	 * @param $catalogueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogueNewData,$catalogueId){
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('IntelligenceCataloguePermission')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$this->model('IntelligenceCataloguePermission')->data($catalogueNewData)->where($where)->save();
		}
	}
	
	public function adjustDocumentationQuantity($catalogueId,$quantity = 1){
		$catalogueId = $this->getInt($catalogueId);
		if(!$catalogueId){
			return 0;
		}
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		if(strpos($quantity,'-') !== false){
			$this->model('IntelligenceCataloguePermission')->where($where)->setDec('documentation_total',substr($quantity,1));
		}else{
			$this->model('IntelligenceCataloguePermission')->where($where)->setInc('documentation_total',$quantity);
		}
		
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $catalogueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($catalogueNewData){
		
		$catalogueNewData['subscriber_identity'] =$this->session('uid');
		$catalogueNewData['dateline'] = $this->getTime();
		$catalogueNewData['sn'] = $this->get_sn();
			
		$catalogueNewData['lastupdate'] = $catalogueNewData['dateline'];
		$catalogueId = $this->model('IntelligenceCataloguePermission')->data($catalogueNewData)->add();
        if($catalogueId){
        }
	    return $catalogueId;
	}
}