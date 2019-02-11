<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: doc.php 2016-07-28 10:21:25Z jianqimin $
 */



/** 
 * 文本
 */
class filter_doc
{
	private $data;
	public function filter($data,$format = '')

	{
		$this->data = $data;
		return true;
		return $this->check_url($data);
	}
	
	
	//检测URL
	private function check_url($text)
	{
		
		$regex = '/(ftp|https|http):[\/]{2}[a-z]+[.]{1}[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*/';

		$regex_a = '/[a-z]+[.]{1}[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*/';
		//var_dump(!preg_match($regex,$text) && !preg_match($regex_a,$text)); die();
		//preg_replace("/\s/","",$text);
		return !preg_match($regex,$text) && !preg_match($regex_a,$text);
	}
	
	//检测电话
	private function check_phone($text)
	{
		
		$regex = '/1[3458]{1}\d{9}/';
		return true;
	}
	
	//检测电子邮件
	private function check_email($text)
	{
		
		$regex = '/(ftp|https|http):[\/]{2}[a-z]+[.]{1}[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*/';

		return true;
	}
	private function parse($data)
	{
		
		return is_array($data)?$data:nl2br(strip_tags($data));
	}
	public function __toString()
	{
		if(is_array($this->data))
		{
			foreach($this->data as $key=>$data)
			{
				$this->data[$key] = $this->parse($data);
			}
		}else{
			$this->data = $this->parse($this->data);
		}
		return $this->data;
	}
}