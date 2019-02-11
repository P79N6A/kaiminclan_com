
<?php
/**
 *
 * 单位
 *
 * 页面
 *
 */
class OrganizationMotionService extends Service
{
	public function adjustDepartmentQuantity($MotionId,$quantity = 1){
		
		$MotionId = $this->getInt($MotionId);
		$quantity = $this->getInt($quantity);
		if(!$MotionId || !$quantity){
			return 0;
		}
		
		$where = array(
			'identity'=>$MotionId
		);
		
		if(strpos($quantity,'-') !== false){
			$this->model('OrganizationMotion')->where($where)->setDec('department_num',substr($quantity,1));
		}else{
			$this->model('OrganizationMotion')->where($where)->setInc('department_num',$quantity);
		}
		
		
	}
	/**
	 *
	 * 单位信息
	 *
	 * @param $field 单位字段
	 * @param $status 单位状态
	 *
	 * @reutrn array;
	 */
	public function getMotionList($where,$start,$perpage,$order = 'identity desc'){
		
		$count = $this->model('OrganizationMotion')->where($where)->count();
		if($count){
			$handle = $this->model('OrganizationMotion')->where($where);
			if($order){
				$handle->orderby($order);
			}
			if($perpage){
				$handle->limit($start,$perpage,$count);
			}
			
			$listdata = $handle->select();
			
			
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
	public function checkMotionTitle($title,$cid){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('OrganizationMotion')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 单位信息
	 *
	 * @param $MotionId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getMotionInfo($MotionId,$field = '*'){
		
		$where = array(
			'identity'=>$MotionId
		);
		
		$MotionData = $this->model('OrganizationMotion')->field($field)->where($where)->find();
		
		return $MotionData;
	}
	
	/**
	 *
	 * 单位信息
	 *
	 * @param $MotionId 单位ID
	 *
	 * @reutrn array;
	 */
	public function getMotionData($MotionId){
		
		$where = array(
			'identity'=>$MotionId
		);
		
		$MotionData = $this->model('OrganizationMotion')->field('identity,title')->where($where)->select();
		
		return $MotionData;
	}
	
	/**
	 *
	 * 删除单位
	 *
	 * @param $MotionId 单位ID
	 *
	 * @reutrn int;
	 */
	public function removeMotionId($MotionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$MotionId
		);
		
		$MotionData = $this->model('OrganizationMotion')->where($where)->find();
		if($MotionData){
			
			$output = $this->model('OrganizationMotion')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 单位修改
	 *
	 * @param $MotionId 单位ID
	 * @param $MotionNewData 单位数据
	 *
	 * @reutrn int;
	 */
	public function update($MotionNewData,$MotionId){
		$where = array(
			'identity'=>$MotionId
		);
		
		$MotionData = $this->model('OrganizationMotion')->where($where)->find();
		if($MotionData){
			
			$MotionNewData['lastupdate'] = $this->getTime();
			$this->model('OrganizationMotion')->data($MotionNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新单位
	 *
	 * @param $MotionNewData 单位数据
	 *
	 * @reutrn int;
	 */
	public function insert($MotionNewData){
		
		$MotionNewData['subscriber_identity'] =$this->session('uid');
		$MotionNewData['dateline'] = $this->getTime();
		$MotionNewData['sn'] = $this->get_sn();
			
		$MotionNewData['lastupdate'] = $MotionNewData['dateline'];
		$MotionId = $this->model('OrganizationMotion')->data($MotionNewData)->add();
		return $MotionId;
	}
}