<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class InvestmentProjectService extends Service
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
	public function getProjectList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('InvestmentProject')->where($where)->count();
		if($count){
			$handle = $this->model('InvestmentProject')->where($where);
			if($start && $perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			
			$originateIds = $catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['industry_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>IntelligenceDocumentationModel::getStatusTitle($data['status'])
				);
			}
			
			$industryData = $this->service('InvestmentCatalog')->getCatalogInfo($catalogueIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['industry'] = isset($industryData[$data['industry_identity']])?$industryData[$data['industry_identity']]:array();
			}
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkProjectTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('InvestmentProject')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $proejctId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getProjectInfo($proejctId,$field = '*'){
		
		$where = array(
			'identity'=>$proejctId
		);
		
		$proejctData = $this->model('InvestmentProject')->field($field)->where($where)->find();
		
		return $proejctData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $proejctId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeProjectId($proejctId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$proejctId
		);
		
		$proejctData = $this->model('InvestmentProject')->where($where)->find();
		if($proejctData){
			
			$output = $this->model('InvestmentProject')->where($where)->delete();
			
			$this->service('PaginationItem')->removeProjectIdAllItem($proejctId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $proejctId 模块ID
	 * @param $proejctNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($proejctNewData,$proejctId){
		$where = array(
			'identity'=>$proejctId
		);
		
		$proejctData = $this->model('InvestmentProject')->where($where)->find();
		if($proejctData){
			
			$proejctNewData['lastupdate'] = $this->getTime();
			$this->model('InvestmentProject')->data($proejctNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $proejctNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($proejctNewData){
		
		$proejctNewData['subscriber_identity'] =$this->session('uid');
		$proejctNewData['dateline'] = $this->getTime();
		$proejctNewData['sn'] = $this->get_sn();
			
		$proejctNewData['lastupdate'] = $proejctNewData['dateline'];
		return $this->model('InvestmentProject')->data($proejctNewData)->add();
	}
	
	public function newProject(){
		
	}
}