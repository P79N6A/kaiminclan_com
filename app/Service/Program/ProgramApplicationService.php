<?php
/**
 *
 * 软件
 *
 * 应用
 *
 */
class ProgramApplicationService extends Service {
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getApplicationList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProgramApplication')->where($where)->count();
		if($count){
			$listdata = $this->model('ProgramApplication')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $applicationId 附件ID
	 *
	 * @reutrn array;
	 */
	public function getAttachmentInfo($applicationId,$field = '*'){
		
		$where = array(
			'identity'=>$applicationId
		);
		
		$applicationData = $this->model('ProgramApplication')->field($field)->where($where)->find();
		if($applicationData){
			$applicationData['catalog'] = $this->service('ResourcesCatalog')->getCatalogInfo($applicationData['catalog_identity'],'identity,title');
			
		}
		
		return $applicationData;
	}
	/**
	 *
	 * 检测应用
	 *
	 * @param $roleName 角色名称
	 *
	 * @reutrn int;
	 */
	public function checkApplicationTitle($roleName){
		if($roleName){
			$where = array(
				'title'=>$roleName
			);
			return $this->model('ProgramApplication')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除附件
	 *
	 * @param $applicationId 附件ID
	 *
	 * @reutrn int;
	 */
	public function removeAttachmentId($applicationId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$applicationId
		);
		
		$applicationData = $this->model('ProgramApplication')->where($where)->find();
		if($applicationData){
			
			$output = $this->model('ProgramApplication')->where($where)->delete();
			$this->release();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 附件修改
	 *
	 * @param $applicationId 附件ID
	 * @param $applicationNewData 附件数据
	 *
	 * @reutrn int;
	 */
	public function update($applicationNewData,$applicationId){
		$where = array(
			'identity'=>$applicationId
		);
		
		$applicationData = $this->model('ProgramApplication')->where($where)->find();
		if($applicationData){
			
			$applicationNewData['lastupdate'] = $this->getTime();
			$this->model('ProgramApplication')->data($applicationNewData)->where($where)->save();
			$this->release();
		}
	}
	
	/**
	 *
	 * 新附件
	 *
	 * @param $applicationData 附件信息
	 *
	 * @reutrn int;
	 */
	public function insert($applicationData){
		
		$applicationData['subscriber_identity'] = $this->session('uid');
		$applicationData['dateline'] = $this->getTime();
			
		$applicationData['lastupdate'] = $applicationData['dateline'];
		$applicationId = $this->model('ProgramApplication')->data($applicationData)->add();
		if(!$applicationId){
			$this->release();
		}
		
		return $applicationId;
	}
	
	public function release(){
		
		$list = array();
		$where = array();
		$where['status'] = ProgramApplicationModel::PROGRAM_APPLICATION_STATUS_FINISH;
		$appList = $this->model('ProgramApplication')->where($where)->select();
		if($appList){
			$appIds = $functionalIds = array();
			foreach($appList as $key=>$data){
				$appIds[] = $data['identity'];
				$list[] = array(
					'id'=>$data['identity'],
					'title'=>$data['title']
				);
			}
			$functionalList = $this->service('ProgramFunctional')->getListByApplicationIds($appIds);
			if($functionalList){
				foreach($list as $key=>$app){
					foreach($functionalList as $key=>$data){
						if($data['application_identity'] != $app['identity']) continue;
						$functionalIds[] = $data['identity'];
						$app['s'][] = array(
							'id'=>$data['identity'],
							'title'=>$data['title']
						);
					}
					$list[$key] = $app;
				}
			}
			
			$interfaceList = $this->service('ProgramInterface')->getListByFunctionalIds($departmentIds);
			
			if($interfaceList){
				foreach($list as $key=>$app){
					foreach($app['s'] as $cnt=>$func){
						foreach($interfaceList as $col=>$api){
							if($api['functional_identity'] != $func['identity']) continue;
							$list[$key]['s'][$cnt]['s'][] = array(
								'id'=>$api['identity'],
								'title'=>$api['title']
							);
						}
					}
				}
			}
			
		}
		
		$folder = __DATA__.'/json/program';
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		$result = file_put_contents($folder.'/application.json',json_encode($list,JSON_UNESCAPED_UNICODE));
	}
}