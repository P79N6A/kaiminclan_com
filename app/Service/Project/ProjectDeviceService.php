<?php
/**
 *
 * 设备
 *
 * 科技
 *
 */
class ProjectDeviceService extends Service
{
	
	/**
	 *
	 * 设备信息
	 *
	 * @param $field 设备字段
	 * @param $status 设备状态
	 *
	 * @reutrn array;
	 */
	public function getDeviceList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProjectDevice')->where($where)->count();
		if($count){
			$handle = $this->model('ProjectDevice')->where($where);
			if($start && $perpage){
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
	public function checkDeviceTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('ProjectDevice')->where($where)->count();
		}
		return 0;
	}

    /**
     *
     * 获取指定用户加入的项目
     *
     * @return int
     */
	public function getDeviceIdByUid($employeeId){

        $subjectIds = array();

	    $employeeId = intval($employeeId);

	    $where = array(
	        'employee_identity'=>$employeeId
        );
	    $list = $this->model('ProjectLeaguer')->field('subject_identity')->where($where)->select();
	    if($list){
	        foreach ($list as $key=>$data){
	            $subjectIds[] = $data['subject_identity'];
            }
        }
	    return $subjectIds;
    }
	
	/**
	 *
	 * 设备信息
	 *
	 * @param $subjectId 设备ID
	 *
	 * @reutrn array;
	 */
	public function getDeviceInfo($subjectId,$field = '*'){
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('ProjectDevice')->field($field)->where($where)->select();
		
		return $subjectData;
	}
	
	/**
	 *
	 * 删除设备
	 *
	 * @param $subjectId 设备ID
	 *
	 * @reutrn int;
	 */
	public function removeDeviceId($subjectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('ProjectDevice')->where($where)->find();
		if($subjectData){
			
			$output = $this->model('ProjectDevice')->where($where)->delete();
			
			$this->service('PaginationItem')->removeDeviceIdAllItem($subjectId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 设备修改
	 *
	 * @param $subjectId 设备ID
	 * @param $subjectNewData 设备数据
	 *
	 * @reutrn int;
	 */
	public function update($subjectNewData,$subjectId){
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('ProjectDevice')->where($where)->find();
		if($subjectData){
			
			$subjectNewData['lastupdate'] = $this->getTime();
			$this->model('ProjectDevice')->data($subjectNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新设备
	 *
	 * @param $subjectNewData 设备数据
	 *
	 * @reutrn int;
	 */
	public function insert($subjectNewData){
		
		$subjectNewData['subscriber_identity'] =$this->session('uid');
		$subjectNewData['dateline'] = $this->getTime();
		$subjectNewData['sn'] = $this->get_sn();
			
		$subjectNewData['lastupdate'] = $subjectNewData['dateline'];
		return $this->model('ProjectDevice')->data($subjectNewData)->add();
	}
}