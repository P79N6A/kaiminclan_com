<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: dateline.php 2016-07-28 10:21:25Z jianqimin $
 */




/** 
 * 时间
 */
class filter_date
{
	private $data;
	private $format = '';
	public function filter($data,$format = '')

	{
		$this->data = $data;
		$this->format = $format;
		return true;
	}
	public function __toString()
	{
		if($this->format == 'dateline')
		{
			$this->data = strtotime($this->data);
		}
		return $this->data;
	}
}