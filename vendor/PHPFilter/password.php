<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: password.php 2016-07-28 10:21:25Z jianqimin $
 */




/** 
 * 密码
 */
class filter_password
{
	private $data;
	public function filter($data,$format = '')
	{
		$this->data = $data;
		if(strlen($data) < 6)
		{
			return false;
		}
		if(preg_match('/^[A-Z]+$/',$data) || preg_match('/^[a-z]+$/',$data) || preg_match('/^[0-9]+$/',$data))
		{
			//return false;
		}
		return true;
	}
	public function __toString()
	{
		return $this->data;
	}
}