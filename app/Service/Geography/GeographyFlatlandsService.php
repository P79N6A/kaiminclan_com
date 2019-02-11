<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class GeographyFlatlandsService extends Service
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
	public function getFlatlandsList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('GeographyFlatlands')->where($where)->count();
		if($count){
			$handle = $this->model('GeographyFlatlands')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
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
	public function checkFlatlandsTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('GeographyFlatlands')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $flatlandsId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getFlatlandsInfo($flatlandsId,$field = '*'){
		
		$where = array(
			'identity'=>$flatlandsId
		);
		
		$flatlandsData = $this->model('GeographyFlatlands')->field($field)->where($where)->find();
		
		return $flatlandsData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $flatlandsId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeFlatlandsId($flatlandsId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$flatlandsId
		);
		
		$flatlandsData = $this->model('GeographyFlatlands')->where($where)->find();
		if($flatlandsData){
			
			$output = $this->model('GeographyFlatlands')->where($where)->delete();
			
			$this->service('PaginationItem')->removeFlatlandsIdAllItem($flatlandsId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $flatlandsId 模块ID
	 * @param $flatlandsNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($flatlandsNewData,$flatlandsId){
		$where = array(
			'identity'=>$flatlandsId
		);
		
		$flatlandsData = $this->model('GeographyFlatlands')->where($where)->find();
		if($flatlandsData){
			
			$flatlandsNewData['lastupdate'] = $this->getTime();
			$this->model('GeographyFlatlands')->data($flatlandsNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $flatlandsNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($flatlandsNewData){
		
		$flatlandsNewData['subscriber_identity'] =$this->session('uid');
		$flatlandsNewData['dateline'] = $this->getTime();
		$flatlandsNewData['sn'] = $this->get_sn();
			
		$flatlandsNewData['lastupdate'] = $flatlandsNewData['dateline'];
		$this->model('GeographyFlatlands')->data($flatlandsNewData)->add();
	}
}