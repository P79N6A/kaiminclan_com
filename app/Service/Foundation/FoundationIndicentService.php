<?php
/**
 *
 * 事件/日志
 *
 * 基础
 
 一共分为五个级别：DEBUG、INFO、WARN、ERROR和FATAL。
 这五个级别是有顺序的，DEBUG < INFO < WARN < ERROR < FATAL，明白这一点很重要，这里Log4j有一个规则：假设设置了级别为P，如果发生了一个级别Q比P高，则可以启动，否则屏蔽掉。
DEBUG: 这个级别最低的东东，一般的来说，在系统实际运行过程中，一般都是不输出的。因此这个级别的信息，可以随意的使用，任何觉得有利于在调试时更详细的了解系统运行状态的东东，比如变量的值等等，都输出来看看也无妨。
INFO：这个应该用来反馈系统的当前状态给最终用户的，所以，在这里输出的信息，应该对最终用户具有实际意义，也就是最终用户要能够看得明白是什么意思才行。从某种角度上说，Info 输出的信息可以看作是软件产品的一部分（就像那些交互界面上的文字一样），所以需要谨慎对待，不可随便。
WARN、ERROR和FATAL：警告、错误、严重错误，这三者应该都在系统运行时检测到了一个不正常的状态，他们之间的区别，要区分还真不是那么简单的事情。我大致是这样区分的：
所谓警告，应该是这个时候进行一些修复性的工作，应该还可以把系统恢复到正常状态中来，系统应该可以继续运行下去。
所谓错误，就是说可以进行一些修复性的工作，但无法确定系统会正常的工作下去，系统在以后的某个阶段，很可能会因为当前的这个问题，导致一个无法修复的错误（例如宕机），但也可能一直工作到停止也不出现严重问题。
所谓Fatal，那就是相当严重的了，可以肯定这种错误已经无法修复，并且如果系统继续运行下去的话，可以肯定必然会越来越乱。这时候采取的最好的措施不是试图将系统状态恢复到正常，而是尽可能地保留系统有效数据并停止运行。
也就是说，选择 Warn、Error、Fatal 中的具体哪一个，是根据当前的这个问题对以后可能产生的影响而定的，如果对以后基本没什么影响，则警告之，如果肯定是以后要出严重问题的了，则Fatal之，拿不准会怎么样，则 Error 之。
 *
 */
class  FoundationIndicentService extends Service {
	
	
	public function newIndicent($title,$remark = '',$level){
		
		$level = intval($level);
		if($level < 1){
			$level = FoundationIndicentModel::FOUNDATION_INDICENT_DEBUG;
			
		}
		if(is_array($remark)){
			$remark = json_encode($remark,JSON_UNESCAPED_UNICODE);
		}
		$where = array();
		$currentTime = $expireTime = 0;
		$currentTime = $this->getTime();
		
		$dateline = 60*60*24;
		switch($level){
			case FoundationIndicentModel::FOUNDATION_INDICENT_ERROR:	$expireTime = $currentTime-$dateline*7; break;
			case FoundationIndicentModel::FOUNDATION_INDICENT_WARN:		$expireTime = $currentTime-$dateline*14; break;
			case FoundationIndicentModel::FOUNDATION_INDICENT_FATAL:	$expireTime = $currentTime-$dateline*20; break;
			case FoundationIndicentModel::FOUNDATION_INDICENT_INFO:		$expireTime = $currentTime-$dateline*27; break;
			case FoundationIndicentModel::FOUNDATION_INDICENT_DEBUG:	$expireTime = $currentTime-$dateline*60; break;
		}
		$where['dateline'] = array('lt',$expireTime);
		
		$where['level'] = $level;
		$this->model('FoundationIndicent')->where($where)->delete();
		$setarr = array(
			'sn'=>$this->get_sn(),
			'title'=>$title,
			'clientip'=>ip2long(__CLIENTIP__),
			'remark'=>$remark,
			'level'=>$level,
			'subscriber_identity'=>$this->session('uid'),
			'dateline'=>$this->getTime()
		);
		 $this->model('FoundationIndicent')->data($setarr)->add();

	}
	/**
	 *
	 *
	 */
	public function error($title,$remark = ''){
		$this->newIndicent($title,$remark,FoundationIndicentModel::FOUNDATION_INDICENT_ERROR);
	}
	/**
	 *
	 *
	 */
	public function warn($title,$remark = ''){
		$this->newIndicent($title,$remark,FoundationIndicentModel::FOUNDATION_INDICENT_WARN);
	}
	/**
	 *
	 *
	 */
	public function fata($title,$remark = ''){
		$this->newIndicent($title,$remark,FoundationIndicentModel::FOUNDATION_INDICENT_FATAL);
	}
	/**
	 *
	 *
	 */
	public function info($title,$remark = ''){
		$this->newIndicent($title,$remark,FoundationIndicentModel::FOUNDATION_INDICENT_INFO);
	}
	/**
	 *
	 *
	 */
	public function debug($title,$remark = ''){
		$this->newIndicent($title,$remark,FoundationIndicentModel::FOUNDATION_INDICENT_DEBUG);
	}
	

	
	/**
	 *
	 * 获取事件列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getIndicentList($where = array(),$orderby = 'identity desc',$start = 1,$perpage = 10){
		$_where = array(
		);
		$where = array_merge($where,$_where);
		
		$count = $this->model('FoundationIndicent')->where($where)->count();
		if($count){
			$listdata = $this->model('FoundationIndicent')->where($where)->orderby($orderby)->limit($start,$perpage,$count)->select();
			
		}
		
		return $listdata;
	}
	
	/**
	 *
	 * 事件信息
	 *
	 * @param $indicentId 事件ID
	 *
	 * @reutrn array;
	 */
	public function getIndicentInfo($indicentId){
		
		$indicentData = array();
		
		$where = array(
			'identity'=>$indicentId
		);
		
		$indicentData = $this->model('FoundationIndicent')->where($where)->select();
		
		if(!is_array($indicentId)){
			$indicentData = current($indicentData);
		}
		
		return $indicentData;
	}
	
	/**
	 *
	 * 删除事件
	 *
	 * @param $indicentId 事件ID
	 *
	 * @reutrn int;
	 */
	public function removeIndicentId($indicentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$indicentId
		);
		
		$indicentData = $this->model('FoundationIndicent')->where($where)->select();
		if($indicentData){
			$output = $this->model('FoundationIndicent')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 事件修改
	 *
	 * @param $indicentId 事件ID
	 * @param $indicentNewData 事件数据
	 *
	 * @reutrn int;
	 */
	public function update($indicentNewData,$indicentId){
		$where = array(
			'identity'=>$indicentId
		);
		
		$indicentData = $this->model('FoundationIndicent')->where($where)->find();
		if($indicentData){
			
			$indicentNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FoundationIndicent')->data($indicentNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新事件
	 *
	 * @param $indicentNewData 事件信息
	 *
	 * @reutrn int;
	 */
	public function insert($indicentNewData){
		$indicentNewData['subscriber_identity'] =$this->session('uid');		
		$indicentNewData['dateline'] = $this->getTime();
			
		$indicentNewData['lastupdate'] = $indicentNewData['dateline'];
		$indicentId = $this->model('FoundationIndicent')->data($indicentNewData)->add();
		
		
	}
}