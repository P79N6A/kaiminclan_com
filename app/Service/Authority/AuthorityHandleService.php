<?php
/**
 *
 * 操作权限
 *
 * 权限
 *
 */
class  AuthorityHandleService extends Service {
	
	/**
	 *
	 * 获取操作权限
	 *
	 * @param $id 数据ID
	 * @param $idtype 数据类型
	 *
	 * @reutrn int;
	 */
	public function getAuthorityHandle($idtype,$id){
		
		$handle = array();
		
		$idtype = intval($idtype);
		$id = intval($id);
		
		$where = array(
			'status'=>AuthorityHandleModel::AUTHORITY_HANDLE_STATUS_ENABLE,
			'id'=>$id,
			'idtype'=>$idtype
		);
		
		$handleList = $this->model('AuthorityHandle')->field('action_identity')->where($where)->select();
		if($handleList){
			foreach($handleList as $key=>$data){
				$handleList[] = $data['action_identity'];
			}
		}
		
		return $handle;
	}
	
	/**
	 *
	 * 删除删除操作
	 *
	 * @param $handleId 操作ID
	 *
	 * @reutrn int;
	 */
	public function removeHandleId($handleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$handleId
		);
		
		$handleData = $this->model('AuthorityHandle')->where($where)->find();
		if($handleData){
			
			$output = $this->model('AuthorityHandle')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 操作修改
	 *
	 * @param $handleId 操作ID
	 * @param $handleNewData 操作数据
	 *
	 * @reutrn int;
	 */
	public function update($handleNewData,$handleId){
		$where = array(
			'identity'=>$handleId
		);
		
		$handleData = $this->model('AuthorityHandle')->where($where)->find();
		if($handleData){
			
			
			$handleNewData['lastupdate'] = $this->getTime();
			$this->model('AuthorityHandle')->data($handleNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新操作
	 *
	 * @param $handleNewData 操作信息
	 *
	 * @reutrn int;
	 */
	public function insert($handleNewData){
		if(!$handleNewData){
			return -1;
		}
			
			
		$handleNewData['subscriber_identity'] =$this->session('uid');
		$handleNewData['dateline'] = $this->getTime();
		$handleNewData['lastupdate'] = $handleNewData['dateline'];
		
		$this->model('AuthorityHandle')->data($handleNewData)->add();
		
	}
}