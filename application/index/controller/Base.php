<?php 
namespace app\index\controller;
use think\Controller;
use  think\Db;
/**
 * 基础控制器类 验证用户是否登陆 验证用户已分配的权限
 */
class Base extends Controller{


	//类的初始化操作

	public  function _initialize(){

		//首先判断session中 userinfo是否有信息 
		// 如果有  用户已经登录 
		// 如果没有  跳转去登录
		
		$userinfo = session('userinfo');

		if(empty($userinfo)){
			$this->redirect("login/index");
		}

		$rulecondition = [];

		//role_id 为1代表是超级管理员 不为1可能有多个权限
		if($userinfo['role_id']!=1){
			$rulecondition['id'] = array('in',session('userinfo.role_rules'));
		}

		//查询该角色下的所有权限
		$rolerule = model('rule')->searchData($rulecondition);//角色所有已分配的权限
		
			$menurule = $allowaction = array();
		foreach ($rolerule as $key => $value) {
			//允许的权限地址  放到一个数组里
			$allowaction[] = $value['rule_address'];
			//如果菜单是可以展示的
			if($value['is_menu']==1){
				//如果pid不等于 0 不是跟权限的话 使用助手函数 将允许的的地址变成url /index/index.html 否则为空 
				
				$value['url'] = $value['pid']!=0 ? url($value['rule_address']) : '';
				//权限地址 处理
				$value['address_name'] = str_replace('/', '_' , $value['rule_address']);
				
				$menurule[] = $value;


			}
		}


		//将请求的控制器使用/拼接方法名  转换成小写
		$nowaction = strtolower(request()->controller() . '/' . request()->action());
		
		//不需要验证的控制器和方法做特殊处理

		$exceptaction = array('index/index','employee/info');

		//将除外的和允许的合并成一个数组
		$allowaction = array_merge($exceptaction,$allowaction);

		//如果不是超级管理员    如果不在除外的操作外  没有权限
		if($userinfo['role_id']!=1){
			//请求的地址没有在允许列表里  则没有权限
			if(!in_array($nowaction,$allowaction)){
				$this->error("没有访问权限，如需请联系管理员","index/index");
			}
		}
		//权限树形结构
		$roletree = getRuleTree($menurule);
		$this->assign('ruletree',$roletree);
		// $this->assign('nowaction',str_replace('/','_',$nowaction));
	}



	

}	