<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: email.php 2016-07-28 10:21:25Z jianqimin $
 */



/** 
 * 电子邮件
 */
class filter_email
{
	private $data;
	public function filter($data,$format = '')
	{
		$this->data = $data;
		if(preg_match('/^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$this->data))
		{
			return false;
		}
		return true;
	}
	public function __toString()
	{
		return $this->data;
	}
}