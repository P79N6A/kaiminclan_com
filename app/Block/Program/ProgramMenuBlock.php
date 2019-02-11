<?php

class ProgramMenuBlock extends Block {
	public function getdata($param){
        $mode = isset($param['mode'])?intval($param['mode']):0;


        $roleId = $this->session('roleId');
        $roleId = $roleId < 1?17:$roleId;
        $roleId = $roleId == 1?17:$roleId;
        $uid = $this->session('uid');
        $menuList = $this->service('AuthorityResources')->getList($roleId,$uid);
		if(!$mode){
			$data = array(
				array('title'=>'我的报告','url'=>'quotation','child'=>array(
					array('title'=>'绩效','url'=>'achievements'),
					array('title'=>'任务','url'=>'mission'),
					array('title'=>'知识','url'=>'knowhow'),
				)),
				array('title'=>'我的任务','url'=>'mission','child'=>array(
					array('title'=>'待处理','url'=>'handle'),
					array('title'=>'已处理','url'=>'product'),
					array('title'=>'工作追踪','url'=>'track'),
					array('title'=>'草稿','url'=>'draft'),
				)),
				array('title'=>'我的知识','url'=>'knowhow','child'=>array(
					array('title'=>'我关注的','url'=>'follow'),
					array('title'=>'我收藏的','url'=>'collection'),
					array('title'=>'我发布的','url'=>'release'),
				)),
				array('title'=>'我的消息','url'=>'messenger','child'=>array(
					array('title'=>'收件箱','url'=>'message'),
					array('title'=>'消息设置','url'=>'profile'),
				)),
				
			);
            $menuList = array();
			foreach($data as $key=>$menu){
                $menuList[] = $menu;
			}
		}
		return array('data'=>$menuList,'total'=>count($data));
	}

	private function push($data){
        $roleId = array();
        foreach($data as $key=>$val){
            $roleId[] = $val['roleId'];
            continue;
            $pageList = $this->model('PaginationPage')->where(array('url'=>array('like','%'.$val['url'].'%')))->select();

            $resourcesList = array();
            foreach($pageList as $cnt=>$page){
                $resourcesList['sn'][] = date('YmdHis');
                $resourcesList['idtype'][] =2;
                $resourcesList['id'][] =$val['roleId'];
                $resourcesList['resources_idtype'][] =3;
                $resourcesList['resources_id'][] = $page['identity'];
                $resourcesList['dateline'][] = time();
                $resourcesList['lastupdate'][] = time();
            }
            $this->model('AuthorityResources')->data($resourcesList)->addMulti();
        }
    }

    public function data(){

        $data = array(
            array('title'=>'系统工具','url'=>'foundation','roleId'=>17,'child'=>array(
                array('title'=>'任务计划','url'=>'mission'),
                array('title'=>'事件查看','url'=>'indicent'),
                array('title'=>'站点','url'=>'website'),
                array('title'=>'数据库','url'=>'database'),
                array('title'=>'文件','url'=>'attachment'),
                array('title'=>'会话','url'=>'session'),
            )),
            array('title'=>'工作流','url'=>'workflow','roleId'=>17,'child'=>array(
                array('title'=>'流程','url'=>'process'),
                array('title'=>'任务','url'=>'mission'),
                array('title'=>'事件','url'=>'incident'),
            )),
            array('title'=>'模板/主题','url'=>'template','roleId'=>17,'child'=>array(
                array('title'=>'主题','url'=>'theme'),
                array('title'=>'布局','url'=>'layout'),
                array('title'=>'模块','url'=>'modular'),
                array('title'=>'文件','url'=>'document'),
            )),
            array('title'=>'应用/平台','url'=>'foundation','roleId'=>17,'child'=>array(
                array('title'=>'域名','url'=>'domain'),
                array('title'=>'平台','url'=>'platform'),
                array('title'=>'目录','url'=>'catalogue'),
                array('title'=>'页面','url'=>'pagination'),
            )),
            array('title'=>'账户与组','url'=>'authority','roleId'=>17,'child'=>array(
                array('title'=>'账户','url'=>'subscriber'),
                array('title'=>'组','url'=>'role'),
            )),
            array('title'=>'资源中心','url'=>'pagination','roleId'=>10,'child'=>array(
                array('title'=>'页面','url'=>'page'),
                array('title'=>'模块','url'=>'block'),
                array('title'=>'数据','url'=>'item'),
            )),
            array('title'=>'消息中心','url'=>'messenger','roleId'=>17,'child'=>array(
                array('title'=>'消息','url'=>'message'),
                array('title'=>'模板','url'=>'template'),
            )),
            array('title'=>'服务和应用程序','url'=>'program','roleId'=>17,'child'=>array(
                array('title'=>'程序和功能','url'=>'application'),
                array('title'=>'服务','url'=>'service'),
            )),
            array('title'=>'安全中心','url'=>'security','roleId'=>17,'child'=>array(
                array('title'=>'备份','url'=>'backup'),
                array('title'=>'还原','url'=>'reduction'),
                array('title'=>'敏感词','url'=>'keenness'),
                array('title'=>'关键字','url'=>'keyword'),
                array('title'=>'黑名单','url'=>'blacklist'),
                array('title'=>'异常','url'=>'abnormal'),
            )),
            array('title'=>'在线管理','url'=>'interview','roleId'=>17,'child'=>array(
                array('title'=>'设备','url'=>'equipment'),
                array('title'=>'地址','url'=>'clientip'),
                array('title'=>'访客','url'=>'visitor'),
                array('title'=>'足迹','url'=>'footmark'),
            )),
            array('title'=>'报表','url'=>'quotation','roleId'=>17,'child'=>array(
                array('title'=>'科目','url'=>'principal'),
                array('title'=>'指标','url'=>'indicatrix'),
            )),
            //何人，何地，用什么设备，何时，看了什么
            array('title'=>'基金','url'=>'fund','roleId'=>10,'child'=>array(
                array('title'=>'分类','url'=>'catalogue'),
                array('title'=>'产品','url'=>'product'),
                array('title'=>'成份','url'=>'reconstituent'),
                array('title'=>'头寸','url'=>'quotient'),
            )),
            array('title'=>'基础','url'=>'mechanism','roleId'=>8,'child'=>array(
                array('title'=>'银行卡','url'=>'bankcard'),
                array('title'=>'账户','url'=>'account'),
                array('title'=>'科目','url'=>'subject'),
                array('title'=>'分类','url'=>'typological'),
                array('title'=>'货币','url'=>'currency'),
            )),
            array('title'=>'产业','url'=>'intercalate','roleId'=>9,'child'=>array(
                array('title'=>'经纪','url'=>'broker'),
                array('title'=>'监管','url'=>'supervise'),
                array('title'=>'交易所','url'=>'exchange'),
            )),
            array('title'=>'资金','url'=>'bankroll','roleId'=>25,'child'=>array(
                array('title'=>'转入','url'=>'revenue'),
                array('title'=>'转出','url'=>'expenses'),
                array('title'=>'调账','url'=>'adjustment'),
                array('title'=>'流水','url'=>'subsidiary'),
                array('title'=>'账户','url'=>'account'),
                array('title'=>'托管','url'=>'trusteeship'),
            )),
            array('title'=>'市场','url'=>'posture','roleId'=>24,'child'=>array(
                array('title'=>'价格','url'=>'quotation'),
                array('title'=>'信号','url'=>'signal'),
                array('title'=>'机会','url'=>'opportunity'),
                array('title'=>'指标','url'=>'indicators'),
            )),
            array('title'=>'方案','url'=>'fight','roleId'=>24,'child'=>array(
                array('title'=>'类型','url'=>'channel'),
                array('title'=>'策略','url'=>'policy'),
            )),
            array('title'=>'资产','url'=>'property','roleId'=>18,'child'=>array(
                array('title'=>'主体','url'=>'capital'),
                array('title'=>'等级','url'=>'scale'),
                array('title'=>'战区','url'=>'theater'),
            )),
            array('title'=>'头寸','url'=>'position','roleId'=>27,'child'=>array(
                array('title'=>'开仓','url'=>'purchase'),
                array('title'=>'平仓','url'=>'shipments'),
                array('title'=>'库存','url'=>'inventory'),
            )),
            array('title'=>'资金往来','url'=>'dealings','roleId'=>8,'child'=>array(
                array('title'=>'收入','url'=>'revenue'),
                array('title'=>'支出','url'=>'expenses'),
                array('title'=>'调账','url'=>'adjustment'),
                array('title'=>'应收','url'=>'receivable'),
                array('title'=>'应付','url'=>'payable'),
                array('title'=>'账户流水','url'=>'subsidiary'),
            )),
            array('title'=>'预算','url'=>'budget','roleId'=>8,'child'=>array(
                array('title'=>'分类','url'=>'classify'),
                array('title'=>'科目','url'=>'subject'),
                array('title'=>'计划','url'=>'project'),
                array('title'=>'执行','url'=>'procedure'),
            )),
            array('title'=>'融资','url'=>'permanent','roleId'=>8,'child'=>array(
                array('title'=>'分类','url'=>'fashion'),
                array('title'=>'债务','url'=>'indebtedness'),
                array('title'=>'授信','url'=>'credit'),
            )),
            array('title'=>'投资','url'=>'investment','roleId'=>8,'child'=>array(
                array('title'=>'产业','url'=>'catalog'),
                array('title'=>'项目','url'=>'project'),
                array('title'=>'债权','url'=>'obligatory'),
            )),
            //15
            array('title'=>'研发','url'=>'production','roleId'=>14,'child'=>array(
                array('title'=>'设计图','url'=>'design'),
                array('title'=>'页面','url'=>'frontend'),
                array('title'=>'业务流程','url'=>'process'),
                array('title'=>'平台','url'=>'platform'),
                array('title'=>'目录','url'=>'channel'),
                array('title'=>'需求','url'=>'demand'),
                array('title'=>'产品','url'=>'product'),
            )),
            array('title'=>'生产','url'=>'fabrication','roleId'=>14,'child'=>array(
                array('title'=>'接口','url'=>'joggle'),
                array('title'=>'应用','url'=>'application'),
                array('title'=>'功能','url'=>'functional'),
                array('title'=>'插件','url'=>'plugin'),
            )),
            //16
            array('title'=>'质量','url'=>'faultiness','roleId'=>14,'child'=>array(
                array('title'=>'用例','url'=>'example'),
                array('title'=>'缺陷','url'=>'bulletin'),
                array('title'=>'用例类型','url'=>'catalogue'),
                array('title'=>'测试类型','url'=>'channel'),
                array('title'=>'测试计划','url'=>'quality'),
                array('title'=>'版本发布','url'=>'release'),
            )),
            array('title'=>'友情链接','url'=>'friendship','roleId'=>10,'child'=>array(
                array('title'=>'分类','url'=>'classify'),
                array('title'=>'网站','url'=>'website'),
            )),
            array('title'=>'售后','url'=>'opportunity','roleId'=>10,'child'=>array(
                array('title'=>'回访','url'=>'visit'),
                array('title'=>'投诉','url'=>'complain'),
                array('title'=>'退货/退款','url'=>'drawback'),
                array('title'=>'报修','url'=>'repair'),
                array('title'=>'物流配送','url'=>'express'),
            )),
            array('title'=>'渠道','url'=>'passageway','roleId'=>10,'child'=>array(
                array('title'=>'分类','url'=>'channel'),
                array('title'=>'渠道','url'=>'alleyway'),
            )),
            array('title'=>'销售','url'=>'market','roleId'=>10,'child'=>array(
                array('title'=>'订单','url'=>'orderdd'),
            )),
            array('title'=>'促销','url'=>'promotion','roleId'=>10,'child'=>array(
                array('title'=>'活动','url'=>'flexible'),
                array('title'=>'优惠卷','url'=>'coupon'),
                array('title'=>'类型','url'=>'style'),
            )),
            array('title'=>'客户','url'=>'customer','roleId'=>10,'child'=>array(
                array('title'=>'等级','url'=>'distinction'),
                array('title'=>'客户','url'=>'clientete'),
            )),
            array('title'=>'商机','url'=>'requirement','roleId'=>10,'child'=>array(
                array('title'=>'分类','url'=>'catalogue'),
                array('title'=>'需求','url'=>'demand'),
            )),
            array('title'=>'分销','url'=>'distribution','roleId'=>10,'child'=>array(
                array('title'=>'代理','url'=>'agent'),
                array('title'=>'级别','url'=>'grade'),
                array('title'=>'产品','url'=>'product'),
                array('title'=>'订单','url'=>'orderdd'),
                array('title'=>'佣金','url'=>'brokerage'),
            )),
            array('title'=>'组织机构','url'=>'organization','roleId'=>6,'child'=>array(
                array('title'=>'单位','url'=>'company'),
                array('title'=>'部门','url'=>'department'),
                array('title'=>'员工','url'=>'employee'),
                array('title'=>'职位','url'=>'position'),
                array('title'=>'头衔','url'=>'harbour'),
                array('title'=>'职称','url'=>'technical'),
                array('title'=>'岗位','url'=>'quarters'),
            )),
            array('title'=>'薪酬与福利','url'=>'remuneration','roleId'=>6,'child'=>array(
                array('title'=>'福利','url'=>'welfare'),
                array('title'=>'薪资','url'=>'salary'),
                array('title'=>'假期','url'=>'holiday'),
                array('title'=>'休假','url'=>'furlough'),
            )),
            array('title'=>'绩效与考核','url'=>'priceless','roleId'=>6,'child'=>array(
                array('title'=>'绩效','url'=>'achievements'),
                array('title'=>'科目','url'=>'subject'),
                array('title'=>'行为','url'=>'behavior'),
                array('title'=>'总结','url'=>'reorganize'),
                array('title'=>'计划','url'=>'prospectus'),
            )),
            array('title'=>'招聘培训','url'=>'recruitment','roleId'=>6,'child'=>array(
                array('title'=>'渠道','url'=>'medium'),
                array('title'=>'岗位','url'=>'quarters'),
                array('title'=>'人才','url'=>'personnel'),
                array('title'=>'培训','url'=>'cultivate'),
            )),
            array('title'=>'制度与文化','url'=>'civilization','roleId'=>6,'child'=>array(
                array('title'=>'栏目','url'=>'column'),
                array('title'=>'文章','url'=>'article'),
            )),
            array('title'=>'地理','url'=>'geography','roleId'=>9,'child'=>array(
                array('title'=>'地区','url'=>'district'),
                array('title'=>'民族','url'=>'nationality'),
                array('title'=>'宗教','url'=>'religion'),
                array('title'=>'江河','url'=>'rivers'),
                array('title'=>'山脉','url'=>'mountain'),
                array('title'=>'平原','url'=>'flatlands'),
            )),
            array('title'=>'知识库','url'=>'knowledge','roleId'=>9,'child'=>array(
                array('title'=>'知识','url'=>'knowhow'),
                array('title'=>'目录','url'=>'catalogue'),
                array('title'=>'文档','url'=>'documentation'),
            )),
            array('title'=>'行情','url'=>'quotation','roleId'=>9,'child'=>array(
                array('title'=>'债券','url'=>'bond'),
                array('title'=>'股票','url'=>'stock'),
                array('title'=>'外汇','url'=>'forex'),
                array('title'=>'大宗商品','url'=>'futures'),
            )),
            array('title'=>'新闻','url'=>'intelligence','roleId'=>9,'child'=>array(
                array('title'=>'栏目','url'=>'catalogue'),
                array('title'=>'来源','url'=>'originate'),
                array('title'=>'草稿','url'=>'draft'),
                array('title'=>'文章','url'=>'documentation'),
            )),
            array('title'=>'证券','url'=>'securities','roleId'=>9,'child'=>array(
                array('title'=>'概念','url'=>'concept'),
                array('title'=>'行业','url'=>'industry'),
                array('title'=>'股票','url'=>'stock'),
                array('title'=>'红利','url'=>'dividend'),
            )),
            array('title'=>'大宗','url'=>'material','roleId'=>9,'child'=>array(
                array('title'=>'目录','url'=>'catalog'),
                array('title'=>'产品','url'=>'product'),
            )),
            array('title'=>'外汇','url'=>'foreign','roleId'=>9,'child'=>array(
                array('title'=>'货币','url'=>'currency'),
                array('title'=>'合约','url'=>'contact'),
            )),
            array('title'=>'债券','url'=>'debenture','roleId'=>9,'child'=>array(
                array('title'=>'类型','url'=>'catalogue'),
                array('title'=>'债券','url'=>'bond'),
            )),
        );
    }

}