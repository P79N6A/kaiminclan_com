<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: chinese.php 2016-07-28 10:21:25Z jianqimin $
 */



/** 
 * 中文
 */
class filter_chinese
{
	private $data;
	private function format($data)
	{
		return !preg_match('/^[A-Za-z0-9]+$/',$data);
	}
	public function filter($data,$format = '')
	{
		$this->data = $data;
		if(is_array($data))
		{
			foreach($data as $key=>$val)
			{
				if(!$this->format($val))
				{
					return false;
				}
			}
		}else{
			if(!$this->format($data))
			{
				return false;
			}
		}
		return true;
	}
	public function __toString()
	{
		return nl2br($this->data);
	}
}