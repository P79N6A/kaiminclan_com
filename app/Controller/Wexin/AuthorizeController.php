<?php
/**
 * 授权
 *
 * 微信管理
 */
class AuthorizeController extends Controller {
	protected $permission = 'realtime';
	
    protected $method = 'POST';
	
    protected function setting(){
        return array(
			'code'=>array('type'=>'string','tooltip'=>'微信CODE')
        );
	}

	public function fire(){
		$code = $this->argument('code');
		
		$connectData = $this->service('AuthorityConnect')->getOpenid($code);
		$openid = $connectData['openid'];
		$relationData = $this->service('AuthorityRelation')->getRelationByWeixinOpenId($openid);
		if(!$relationData){
			$relationId = $this->service('AuthorityRelation')->newWeixin($openid,$connectData['nickname'],$connectData['headimgurl']);
		}else{
			if($relationData['fullname'] != $connectData['nickname'] || $relationData['attachment_identity'] != $connectData['headimgurl']){
				$this->service('AuthorityRelation')->update(array('fullname'=>$connectData['nickname'],'attachment_identity'=>$connectData['headimgurl']),$relationData['identity']);
			}
			$relationId = $relationData['identity'];
		}
		$this->cookie('openId',$openid);
		$this->cookie('relationId',$relationId);
		
		$this->assign('openId',$openid);
	}
}