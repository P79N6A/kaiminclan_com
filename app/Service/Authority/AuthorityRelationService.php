<?php
/**
 *
 * 身份
 *
 * 权限
 *
 */
class  AuthorityRelationService extends Service {
	
	public function getRelationInfo($relationIds){
		
		$relationData = array();
		
		$where = array(
			'identity'=>$relationIds
		);
		
		$listdata = $this->model('AuthorityRelation')->where($where)->select();
		if($listdata){
			$wechatIds = array();
			foreach($listdata as $key=>$data){
				$wechatIds[] = $data['id'];
			}
			
			$relationData = $listdata;
		}
		
		
		return $relationData;
	}
	
	public function getRelationByWeixinOpenId($openId){
		$where = array();
		$where['code'] = $openId;
		$where['platform'] = AuthorityRelationModel::AUTHORITY_RELATION_PLATFORM_WEIXIN;
		
		return $this->model('AuthorityRelation')->where($where)->find();
	}
	
	public function newWeixin($openId,$fullname = '',$attachUrl = ""){
		if(!$openId){
			return 0;
		}
		
		$wechatId = 0;
		$siteUrl = 'http://'.__SITE_URL__;
		if(defined('__SUB_HOST__')){
			$siteUrl = $siteUrl.'/'.__SUB_HOST__.'/';
		}
		
		$setarr = array(
			'code'=>$openId,
			'id'=>$wechatId,
			'fullname'=>$fullname,
			'attachment_identity'=>$attachUrl,
			'platform'=>AuthorityRelationModel::AUTHORITY_RELATION_PLATFORM_WEIXIN
		);
		
		return $this->insert($setarr);
	}
	
	public function insert($relationData){

        $relationData['sn'] = $this->get_sn();
        $relationData['dateline'] = $this->getTime();
        $relationData['lastupdate'] = $relationData['dateline'];

        return $this->model('AuthorityRelation')->data($relationData)->add();
    }

    public function update($relationData,$relationId){
		
		$relationId = $this->getInt($relationId);
		if(!$relationId){
			return 0;
		}
		$relationData['lastupdate'] = $relationData['dateline'];
		
		return $this->model('AuthorityRelation')->data($relationData)->where(array('identity'=>$relationId))->save();
	}
}