<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: url.php 2016-07-28 10:21:25Z jianqimin $
 */



/** 
 * 超链接
 */
class filter_url
{
	private $data;
	public function filter($data,$format = '')

	{
		$this->data = $data;
		
		if(!preg_match('/[http|https]:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$this->data) && $this->data)
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