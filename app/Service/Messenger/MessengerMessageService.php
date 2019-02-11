<?php
/**
 *　
 * 消息管理
 *
 * 推送
 *
 */
class MessengerMessageService extends Service {
	
	//站内
	const MESSAGE_TYPE_DEFAULT = 1;
	//电子邮件
	const MESSAGE_TYPE_MAIL = 2;
	//短信
	const MESSAGE_TYPE_MMS = 3;
	//公众号
	const MESSAGE_TYPE_WECHAT = 4;
	
	const SIGN = '手杖与蛇';
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getMessageList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MessengerMessage')->where($where)->count();
		if($count){
			$listdata = $this->model('MessengerMessage')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 检测目标是否锁定
	 
	 * @param $purpose 数据，支持手机号，电子邮件
	 * 
	 * @return mixed
	 *
	 */
	public function isLocked($purpose){
		
		$where = array(
			'purpose'=>$purpose
		);
		
		$codeData = $this->model('MessengerMessage')->field('expire_time')->where($where)->find();
		if($codeData){
			return $this->getTime()-$codeData['expire_time'];
		}
		
		return 0;
	}
	/**
	 *
	 * 检验验证码
	 * @param $code 类型
	 * 
	 * @return mixed
	 *
	 */
	public static function checkVerifyCode($code){
		
		if(defined('__APP_DEBUG__') && __APP_DEBUG__ == true){
			return false;
		}
		$where = array();
		$where['code'] = $code;
		$codeData = $this->model('MessengerMessage')->field('purpose')->where($where)->find();
		if(!$codeData){
			return true;
		}
		
		return false;
	}
	/**
	 *
	 * 发送消息
	 * @param $type 类型
	 * @param $to 发送目标
	 * @param $subject 主题
	 * @param $content 内容
	 * @param $param 其他参数
	 * 
	 * @return mixed
	 *
	 */
	public function send($type,$to,$subject,$content,$param = array(),$expire_time = 60*60){
		$code = $this->getCode();
		if(strpos($content,'{CODE}') !== FALSE){
			$content = str_replace('{CODE}',$code,$content);
		}
		if(strpos($content,'{code}') !== FALSE){
			$content = str_replace('{code}',$code,$content);
		}
		
		$setarr = array(
			'title'=>$subject,
			'content'=>$content,
			'purpose'=>is_array($to)?json_encode($to):$to,
			'code'=>$code,
			'send_date'=>$this->getTime(),
			'subscriber_identity'=>$this->session('uid'),
			'dateline'=>$this->getTime(),
			'lastupdate'=>$this->getTime()
		);
		
		
		$status = 0;
		switch($type){
			case self::MESSAGE_TYPE_MAIL: $status = $this->sendMailMms($to,$subject,$content);break;
			case self::MESSAGE_TYPE_MMS: $status = $this->sendMobileMms($to,$content); break;
			case self::MESSAGE_TYPE_WECHAT: $status = $this->sendWebchatMms($to,$content);break;
		}
		$setarr['status'] = $status;
		$this->model('MessengerMessage')->data($setarr)->add();
		
		
		
	}
	
	private function getCode(){
		
		$result = array();
		$str = '123456790';
		
		for($i=0;$i<6;$i++){
			$result[] = substr($str,mt_rand(0,strlen($str)-2),1);
		}
		
		return implode('',$result);
	}
	/**
	 * 邮件
	 * @param $toMail 收件地址
	 * @param $subject 主题
	 * @param $content 内容
	 * 
	 * @return mixed
	 */
	public function sendMailMms($toMail,$subject,$content){
		
		include_once __ROOT__.'/vendor/PHPBamboo/extend/PHPMailer/class.phpmailer.php';
		include_once __ROOT__.'/vendor/PHPBamboo/extend/PHPMailer/class.smtp.php';
		include_once __ROOT__.'/vendor/PHPBamboo/extend/PHPMailer/class.pop3.php';
		
		$setting = $this->config('message.mail');
		
		$msg = 'no msg';
		try { 
			$mail = new PHPMailer(true); 
			
			$mail->IsSMTP(); 
			
			$mail->CharSet=$setting['charset']; //设置邮件的字符编码，这很重要，不然中文乱码 
			
			$mail->SMTPAuth = $setting['auth']; //开启认证 
			$mail->Port = $setting['port']; 
			$mail->Host = $setting['host']; 
			$mail->Username = $setting['username']; 
			$mail->Password = $setting['password'];
			 
			$mail->From = $setting['source']; 
			$mail->FromName = $setting['from']; 
			
			$mail->AddAddress($toMail); 
			$mail->Subject = $subject; 
			
			$mail->Body = $content; 
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略 
			
			$mail->WordWrap = 80; // 设置每行字符串的长度 
			//$mail->AddAttachment("f:/test.png"); //可以添加附件 
			$mail->IsHTML(true); 
			if($mail->Send()){
				return 1; 
			}else{
				return 0;
			}
		} catch (phpmailerException $e) { 
			$msg = $e->errorMessage(); 
		}
		return $msg; 
	}
	/**
	 * 短信信息
	 * @param $mobile 手机号码
	 * @param $subject 主题
	 * @param $content 内容
	 * 
	 * @return mixed
	 */
	public function sendMobileMms($mobile,$content){
		
		$setting = $this->config('message.mms');
		
		$param = array(
			'message'=>$setting['username'],
			'password'=>$setting['password'],
			'phone'=>$mobile,
			'msg'=>'【'.self::SIGN.'】'.urlencode($content)
		);
		
		$result = 0;
		$response = $this->helper('curl')->init($setting['host'])->data($param,2)->fetch();
		
		if($response){
			$result = 1;
		}
		return $result;
	}
	/**
	 * 公众号
	 * @param $openId OPEN_ID
	 * @param $subject 主题
	 * @param $content 内容
	 * 
	 * @return mixed
	 */
	public function sendWebchatMms($openId,$templateId,$jumpUrl,$postData){
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->service('WeixinCommon')->getAccessToken();
		
		$param = array(
			'touser'=>$openId,
			'template_id'=>$templateId,
			'url'=>$jumpUrl,
			'data'=>array(
            'first'=>array('value'=>'test','color'=>'#173177'),
            'keynote1'=>array('value'=>'test','color'=>'#173177'),
            'keynote2'=>array('value'=>'test','color'=>'#173177'),
            'remark'=>array('value'=>'test','color'=>'#173177'),
        )
		);
		
		$result = 0;
		$response = $this->helper('curl')->init($url)->data($param,2)->fetch();
		
		if($response){
			$result = 1;
		}
		return $result;
	}
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $messageId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeMessageId($messageId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$messageId
		);
		
		$messageData = $this->model('MessengerMessage')->where($where)->count();
		if($messageData){
			
			$output = $this->model('MessengerMessage')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $messageId 收藏ID
	 * @param $messageNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($messageNewData,$messageId){
		$where = array(
			'identity'=>$messageId
		);
		
		$messageData = $this->model('MessengerMessage')->where($where)->find();
		if($messageData){
			
			
			$messageNewData['lastupdate'] = $this->getTime();
			$this->model('MessengerMessage')->data($messageNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($messageData){
		$dateline = $this->getTime();
		$messageData['subscriber_identity'] = $this->session('uid');
		$messageData['dateline'] = $dateline;
		$messageData['lastupdate'] = $dateline;
		$messageData['sn'] = $this->get_sn();
			
		
		return $this->model('MessengerMessage')->data($messageData)->add();
		
	}
}