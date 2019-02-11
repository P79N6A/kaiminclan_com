<?php
class PHPSma  {
	/**
	 * 简单移动平均
	 * 算法：(X1+X2+X3+...+Xn)/N
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function sma($sma,$price,$day){
		return (2*$price+($day-1)*$sma)/($day+1);
	}
}