<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: qq.php 2016-07-28 10:21:25Z jianqimin $
 */




/** 
 * 邮政编码
 */
class filter_qq
{
	private $data;
	private function format($data)
	{
		return preg_match('/[1-9][0-9]{4,10}/',$data);
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
		return $this->data;
	}
}