<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: mobile.php 2016-07-28 10:21:25Z jianqimin $
 */



/** 
 * 手机
 */
class filter_mobile
{
	private $data;
	public function filter($data,$format = '')

	{
		$this->data = $data;
		if(!is_numeric($data) || strcmp(strlen($data),11) !== 0)
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