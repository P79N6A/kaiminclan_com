<?php

/**
 *
 * 算法:若y=wma(x,a)，
 * 则y=(n*x0+(n-1)*x1+(n- 2)*x2)+...+1*xn)/(n+(n-1)+(n-2)+...+1)
 * x0表示本周期值，
 * x1表示上一周期值。
 
 加权移动平均值。

用法:WMA(X,A),求X的加权移动平均。

算法: 若Y=WMA(X,A) 则 Y=(N*X0+(N-1)*X1+(N-2)*X2)+...+1*XN)/(N+(N-1)+(N-2)+...+1)X0表示本周期值，X1表示上一周期值...。

例如：WMA(CLOSE,20)表示求20日加权均价。

以上是同花顺软件中公式的解释。



但是同花顺 你丫的搞什么，这个简单的算法你都能出错。



按你说的X0表示本周期值，X1表示上一周期值...。

设X0=10.73 X1=10.67 X2=10.66

Y=wma（x，3）=（3*10.73+2*10.67+1*10.66）/(3+(3-1)+(3-2))

=（3*10.73+2*10.67+1*10.66）/(3+2+1)

= (32.19+21.34+10.66)/6

=64.19/6

=10.6983333

四舍五入 ≈10.70

可是同花顺你实际的wma函数算出来确实10.675 为什么！！

同花顺wma函数实际算法：

同上设X0=10.73 X1=10.67 X2=10.66

Y=wma（x，3）=（3*10.66+2*10.67+1*10.73）/(3+(3-1)+(3-2))

=（3*10.66+2*10.67+1*10.73）/(3+2+1)

= (31.98+21.34+10.73)/6

=64.05/6

=10.675

向下取整 ≈10.67
 */
class PHPWma  {
	private $day = 0;
	
	private $data = array();
	
	private $folder;
	public function __construct ($folder){
		if(!$folder){
			$folder = dirname(__FILE__);
		}
		
		$folder = $folder.'/signal/wma';
		
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		$this->folder = $folder;
		
	}
	
	public function setData($symbol,$data,$day){
		
		$filename = $this->folder.'/'.$symbol.'_'.$day.'.json';
		if(is_file($filename)){
			$historyData = file_get_contents($filename);
			if($historyData){
				$historyData = json_decode($historyData,true);
				if($historyData){
					$dataLen = count($historyData);
					if($dataLen >= $day){
						$historyData = array_slice($historyData,1);
					}
					$this->data=  $historyData;
				}
			}
		}
		$this->data[] = $data;
		
		$this->day = $day;
		
		file_put_contents($filename,json_encode($this->data));
		
		return $this;
	}
	public function getLeft(){
		$leftVal = 0;		
		$dataLen = count($this->data);	
		for($i=$this->day;$i>0;$i--){
			$cnt= $i;
			$cnt--;
			//echo "leftVal:".$i."\r\n";
			if(!isset($this->data[$cnt])) continue;
			$leftVal += $i*$this->data[$cnt];
		}	
		//var_dump($leftVal); die();
		
		return $leftVal;
	}
	
	private function getRight(){
		$rightVal = 0;
		for($i=$this->day;$i> 0;$i--){
			$rightVal += $i;
		}
		return $rightVal;
	}
	/**
	 * 指数平滑移动平均
	 *
	 * @param $price 
	 * @param $day 
	 *
	 */
	public function get(){
		//var_dump($this->getLeft(),$this->getRight());
		return round($this->getLeft()/$this->getRight(),8);
	}
}