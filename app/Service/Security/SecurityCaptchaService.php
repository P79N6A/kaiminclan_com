<?php
/**
 * 验证码
 * 图片验证码
 * 看图识物
 * 位置定位
 */
class SecurityCaptchaService extends Service
{
	
	/** 数字 */
	private $digit = 0123456789;
	/** 字母 */
	private $enBig = 'ABCDEFGHIJKLMNPOPQRSTUVWXYZ';
	
	private $enSmall = 'abcdefghijklmnopqrstuvwxyz';
	
	/** 中文 */
	private $zh = '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借';
	
	private $w = 200;
	private $h = 50;
	/** 数字大写字母结合，数字小写字母组合，数字字母混合，数字中文组合,数字中文字母（小大）组合，数字中文字母大写，数字中文字母小写，*/
	
	/** 纯字母大写*/
	const CAPTCHA_LETTER_BIG = 1;
	/** 纯字母小写*/
	const CAPTCHA_LETTER_SMALL = 2;
	/** 纯数字*/
	const CAPTCHA_DIGITAL = 3;
	/** 纯中文*/
	const CAPTCHA_CHINESE = 4;
	/** 数字大写字母结合*/
	const CAPTCHA_DIGITAL_LETTER_BIG = 5;
	/** 数字小写字母组合*/
	const CAPTCHA_DIGITAL_LETTER_SMALL = 6;
	/** 数字字母*/
	const CAPTCHA_DIGITAL_LETTER = 7;
	/** 数字中文*/
	const CAPTCHA_DIGITAL_CHINESE = 8;
	/** 数字中文字母（小）*/
	const CAPTCHA_DIGITAL_CHINESE_LETTER_SMALL = 9;
	/** 数字中文字母（大）*/
	const CAPTCHA_DIGITAL_CHINESE_LETTER_BIG = 10;
	/** 数字中文字母*/
	const CAPTCHA_DIGITAL_CHINESE_LETTER = 11;
	
	public function init($w = 100,$h=50){
		$this->w = $w;
		$this->h = $h;
		
		$this->mode = 1;
		$this->length = 5;
		
		return $this;
	}
	
	public function remove($str,$removeStr){
		$output = array();
		if(!preg_match('/^\d+$/',$str) && preg_match('/[a-zA-Z]/',$str)){
			
			$str = mb_convert_encoding($str,'utf8','gbk');
			$len = mb_strlen($str);
			
		}else{
			$len = strlen($str);
		}
		for($i =9;$i<$len; $i++){
			$currentStr = mb_substr($str,$i,1);
			if(in_array($currentStr,$removeStr)){
				continue;
			}
			$output[] = $currentStr;
		}
		
		return implode('',$output);
	}
	
	public function getRandom($mode,$length = 4){
		$length = intval($length);
		$length = $length < 4 ?4:$length;
		
		$seedList = array();
		switch($mode){
			case self::CAPTCHA_LETTER_BIG: 
				$sendList = $this->remove($this->enBig,array('O','I','L','P','Q'));
				break;
			case self::CAPTCHA_LETTER_SMALL: 
				$sendList = $this->remove($this->enSmall,array('o','i','l','p','q'));
				break;
			case self::CAPTCHA_DIGITAL: 
				$sendList = $this->digit;
				break;
			case self::CAPTCHA_CHINESE: 
				$sendList = $this->zh;
				break;
			case self::CAPTCHA_DIGITAL_LETTER_BIG: 
				$sendList = $this->remove($this->digit,array(0,1)).$this->removeStr($this->enBig,array('O','I','L','P','Q'));
				break;
			case self::CAPTCHA_DIGITAL_LETTER_SMALL: 
				$sendList = $this->digit.$this->enSmall;		
				$sendList = $this->remove($this->digit,array(0,1)).$this->removeStr($this->enSmall,array('o','i','l','p','q'));		
				break;
			case self::CAPTCHA_DIGITAL_LETTER: 
				$sendList = $this->remove($this->digit,array(0,1)).$this->removeStr($this->enSmall,array('o','i','l','p','q')).$this->removeStr($this->enBig,array('O','I','L','P','Q'));
				break;
			case self::CAPTCHA_DIGITAL_CHINESE: 
				$seedList = $this->digit.$this->zh;
				break;
			case self::CAPTCHA_DIGITAL_CHINESE_LETTER_SMALL: 
				$seedList = $this->remove($this->digit,array(0,1)).$this->zh.$this->removeStr($this->enSmall,array('o','i','l','p','q'));
				break;
			case self::CAPTCHA_DIGITAL_CHINESE_LETTER_BIG: 
				$seedList = $this->remove($this->digit,array(0,1)).$this->zh.$this->removeStr($this->enBig,array('O','I','L','P','Q'));
				break;
			case self::CAPTCHA_DIGITAL_CHINESE_LETTER: 
				$seedList = $this->remove($this->digit,array(0,1)).$this->zh.$this->removeStr($this->enBig,array('O','I','L','P','Q')).$this->removeStr($this->enSmall,array('o','i','l','p','q'));
				break;
		}
		return $sendList;
		
	}
	
	public function output(){
		
		$w = $this->w;
		$h = $this->h;
		$im=imagecreatetruecolor($this->w,$this->h);
		$bkcolor=imagecolorallocate($im,250,250,250);
		imagefill($im,0,0,$bkcolor);

		/***添加干扰***/
		for($i=0;$i<15;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagearc($im,mt_rand(-10,$w),mt_rand(-10,$h),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
		}
		for($i=0;$i<255;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($im,mt_rand(0,$w),mt_rand(0,$h),$fontcolor);
		}
		
		/***********内容*********/
		//字体文件
		if($this->mode == 1){
			$fontIndex = mt_rand(1,5);
			$fontface= __RESOURCES__.'/font/zh'.$fontIndex.'.ttf'; 
		}else{
			$fontIndex = mt_rand(1,10);
			$fontface= __RESOURCES__.'/font/t'.$fontIndex.'.ttf'; 
		}
		
		if(!is_file($fontface)){
			//字体不存在
		}
		
		$str = $this->getRandom($this->mode,$this->length);
		
		$code="";
		$zh_length = mb_strlen($str)-1;
		
		for($i=0;$i<$this->length;$i++){
			$Xi=mt_rand(0,$zh_length);
			if($Xi%2) $Xi+=1;
			$code.= mb_substr($str,$Xi,1);
		}
		
		$this->session('captcha_code',$code);
		
		
		for($i=0;$i<$this->length;$i++){
			$fontcolor=imagecolorallocate($im,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120)); //这样保证随机出来的颜色较深。
			$codex = mb_substr($str,$i,1);
			imagettftext($im,mt_rand(18,28),mt_rand(-60,60),30*$i+20,mt_rand(30,35),$fontcolor,$fontface,$codex);
		}
		
		imagepng($im);
		imagedestroy($im);
	}
	
}