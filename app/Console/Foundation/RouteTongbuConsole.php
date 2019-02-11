<?php
/**
 * 经纪商
 */
class RouteTongbuConsole extends Console {
	
    public function fire(){
		$routeXml = simplexml_load_string(file_get_contents(__ROOT__.'/config/route.xml'));
		
		$permissionData = array(
			'admin'=>1,
			'develop'=>2,
			'guest'=>5,
			'public'=>0,
			'supplier'=>3,
			'user'=>4,
		);
		
		//var_dump($routeXml); die();
		/*
		<option value="3">供应商</option>
                    <option value="1">管理员</option>
                    <option value="2">运营人员</option>
                    <option value="4">客户</option>
                    <option value="5">游客</option>
					*/
		$paginationList = array();
		foreach($routeXml as $key=>$route){
			$paginationList['title'][] = $route->seo->title;
			$paginationList['folder'][] = $route->folder;
			$paginationList['permission'][] = $permissionData[(string)$route->permission];
			$paginationList['primaltplname'][] = $route->template;
			$paginationList['url'][] = $route->url;
			$paginationList['domain'][] = $route->domain;
			
			$setting = array();
			foreach($route->param as $cnt=>$param){
				$setting[] = (array)$param;
			}
			$paginationList['setting'][] = json_encode($setting,JSON_UNESCAPED_UNICODE);
			$paginationList['seotitle'][] = $route->seo->title;
			$paginationList['seokeyword'][] = $route->seo->keyword;
			$paginationList['seodescription'][] = $route->seo->description;
		}
		
		$this->model('FoundationPagination')->data($paginationList)->addMulti();
	}
}