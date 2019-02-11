<?php
/**
 *
 * 页面
 *
 *
 */
class OrganizationHarbourService extends Service
{
	
	/**
	 *
	 * 页面信息
	 *
	 * @param $field 页面字段
	 * @param $status 页面状态
	 *
	 * @reutrn array;
	 */
	public function getHarbourList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('OrganizationHarbour')->where($where)->count();
		if($count){
			$listdata = $this->model('OrganizationHarbour')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
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
	public function checkHarbourTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('OrganizationHarbour')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 页面浏览数
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustHarbourView($pageId,$quantity = 1){
		
		$where = array(
			'identity' =>$pageId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('OrganizationHarbour')->where($where)->setInc('view_num',1);
					break;
				case -1:
					$this->model('OrganizationHarbour')->where($where)->setDec('view_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 短链接
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function getShortUrl(){
	}
	/**
	 *
	 * 页面访问数
	 *
	 * @param $pageId 页面ID
	 * @param $quantity  数量
	 *
	 * @reutrn array;
	 */
	public function adjustVisitView($pageId,$quantity = 1){
		
		$where = array(
			'identity' =>$pageId
		);
		
		if(in_array($quantity,array('1','-1'))){
			switch($quantity){
				case 1:
					$this->model('OrganizationHarbour')->where($where)->setInc('visitor_num',1);
					break;
				case -1:
					$this->model('OrganizationHarbour')->where($where)->setDec('visitor_num',1);
				break;
			}
		}
	}
	
	/**
	 *
	 * 页面信息
	 *
	 * @param $pageId 页面ID
	 *
	 * @reutrn array;
	 */
	public function getHarbourInfo($pageId,$field = '*'){
		
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('OrganizationHarbour')->field($field)->where($where)->find();
		
		return $pageData;
	}
	
	/**
	 *
	 * 删除页面
	 *
	 * @param $pageId 页面ID
	 *
	 * @reutrn int;
	 */
	public function removeHarbourId($pageId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('OrganizationHarbour')->where($where)->find();
		if($pageData){
			
			$output = $this->model('OrganizationHarbour')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 页面修改
	 *
	 * @param $pageId 页面ID
	 * @param $pageNewData 页面数据
	 *
	 * @reutrn int;
	 */
	public function update($pageNewData,$pageId){
		$where = array(
			'identity'=>$pageId
		);
		
		$pageData = $this->model('OrganizationHarbour')->where($where)->find();
		if($pageData){
			
			$pageNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationHarbour')->data($pageNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新页面
	 *
	 * @param $pageNewData 页面数据
	 *
	 * @reutrn int;
	 */
	public function insert($pageNewData){
		
		$pageNewData['subscriber_identity'] =$this->session('uid');
		$pageNewData['dateline'] = $this->getTime();
		$pageNewData['sn'] = $this->get_sn();
			
		$pageNewData['lastupdate'] = $pageNewData['dateline'];
		$this->model('OrganizationHarbour')->data($pageNewData)->add();
	}
}